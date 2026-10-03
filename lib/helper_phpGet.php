<?php

/**
 * @desc Helper class for downloading public HTTP(S) URLs
 * @license http://opensource.org/licenses/GPL-3.0 GPLv3
 * @license http://opensource.org/licenses/LGPL-3.0 LGPLv3
 */

namespace kcfinder;

class phpGet
{
    static public $methods = array('curl', 'fopen', 'http', 'socket');
    static public $urlExpr = '/^https?:\/\//i';

    static public function get($url, $file = null, $method = null, $maxBytes = 10485760)
    {
        if (!self::check_curl() || !self::isSafeUrl($url) || !is_int($maxBytes) || ($maxBytes < 1)) {
            return false;
        }

        $parts = parse_url($url);
        $host = trim($parts['host'], '[]');
        $addresses = self::resolvePublicAddresses($host);
        if (!$addresses) {
            return false;
        }

        $port = isset($parts['port']) ? (int) $parts['port'] : ($parts['scheme'] === 'https' ? 443 : 80);
        $pinnedAddress = strpos($addresses[0], ':') !== false ? '[' . $addresses[0] . ']' : $addresses[0];
        $destination = null;
        $temporary = false;

        if ($file === true) {
            $path = isset($parts['path']) ? $parts['path'] : '';
            $file = basename($path);
        }
        if ($file !== null && is_dir($file)) {
            $path = isset($parts['path']) ? $parts['path'] : '';
            $file = rtrim($file, '/\\') . DIRECTORY_SEPARATOR . basename($path);
        }

        if ($file === null) {
            $destination = fopen('php://temp', 'w+b');
            $temporary = true;
        } else {
            $destination = @fopen($file, 'w+b');
        }
        if ($destination === false) {
            return false;
        }

        $written = 0;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        curl_setopt($curl, CURLOPT_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS);
        curl_setopt($curl, CURLOPT_REDIR_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS);
        curl_setopt($curl, CURLOPT_NOPROXY, '*');
        curl_setopt($curl, CURLOPT_RESOLVE, array($host . ':' . $port . ':' . $pinnedAddress));
        curl_setopt($curl, CURLOPT_USERAGENT, 'KCFinder/' . uploader::VERSION);
        curl_setopt($curl, CURLOPT_WRITEFUNCTION, function ($curlHandle, $data) use ($destination, $maxBytes, &$written) {
            $length = strlen($data);
            if (($written > $maxBytes - $length) || (fwrite($destination, $data) !== $length)) {
                return 0;
            }
            $written += $length;
            return $length;
        });

        $success = curl_exec($curl);
        $status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        if (PHP_VERSION_ID < 80000) curl_close($curl);

        if (($success === false) || ($status < 200) || ($status >= 300)) {
            fclose($destination);
            if (!$temporary) {
                @unlink($file);
            }
            return false;
        }

        if ($temporary) {
            rewind($destination);
            $content = stream_get_contents($destination);
            fclose($destination);
            return $content;
        }

        fclose($destination);
        return $written;
    }

    static public function isSafeUrl($url)
    {
        if (!is_string($url) || ($url === '') || (filter_var($url, FILTER_VALIDATE_URL) === false)) {
            return false;
        }

        $parts = parse_url($url);
        if (!is_array($parts) || !isset($parts['scheme'], $parts['host']) ||
            !in_array(strtolower($parts['scheme']), array('http', 'https'), true) ||
            isset($parts['user']) || isset($parts['pass'])) {
            return false;
        }
        if (isset($parts['port']) && (($parts['port'] < 1) || ($parts['port'] > 65535))) {
            return false;
        }

        return count(self::resolvePublicAddresses(trim($parts['host'], '[]'))) > 0;
    }

    static public function isPublicAddress($address)
    {
        if (filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $packed = inet_pton($address);
            if ($packed === false) {
                return false;
            }

            $embedded = null;
            if ((substr($packed, 0, 10) === str_repeat("\0", 10)) && (substr($packed, 10, 2) === "\xff\xff")) {
                $embedded = inet_ntop(substr($packed, 12, 4));
            } elseif (substr($packed, 0, 12) === str_repeat("\0", 12)) {
                $embedded = inet_ntop(substr($packed, 12, 4));
            } elseif (substr($packed, 0, 2) === "\x20\x02") {
                $embedded = inet_ntop(substr($packed, 2, 4));
            } elseif (substr($packed, 0, 12) === "\x00\x64\xff\x9b" . str_repeat("\0", 8)) {
                $embedded = inet_ntop(substr($packed, 12, 4));
            }

            if (($embedded !== null) && !self::isPublicAddress($embedded)) {
                return false;
            }
        }

        return filter_var(
            $address,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        ) !== false;
    }

    static private function resolvePublicAddresses($host)
    {
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            return self::isPublicAddress($host) ? array($host) : array();
        }

        if (function_exists('idn_to_ascii')) {
            $asciiHost = idn_to_ascii($host, 0, INTL_IDNA_VARIANT_UTS46);
            if ($asciiHost !== false) {
                $host = $asciiHost;
            }
        }
        if (filter_var($host, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) === false) {
            return array();
        }

        $addresses = array();
        if (function_exists('dns_get_record')) {
            $records = @dns_get_record($host, DNS_A | DNS_AAAA);
            if (is_array($records)) {
                foreach ($records as $record) {
                    if (isset($record['ip'])) {
                        $addresses[] = $record['ip'];
                    } elseif (isset($record['ipv6'])) {
                        $addresses[] = $record['ipv6'];
                    }
                }
            }
        }
        if (!$addresses && function_exists('gethostbynamel')) {
            $ipv4 = @gethostbynamel($host);
            if (is_array($ipv4)) {
                $addresses = $ipv4;
            }
        }

        $addresses = array_values(array_unique($addresses));
        foreach ($addresses as $address) {
            if (!self::isPublicAddress($address)) {
                return array();
            }
        }

        return $addresses;
    }

    // Compatibility wrappers keep the old public API while using the safe transport.
    static public function get_curl($url) { return self::get($url); }
    static public function get_fopen($url) { return self::get($url); }
    static public function get_http($url) { return self::get($url); }
    static public function get_socket($url) { return self::get($url); }
    static private function check_curl() { return function_exists('curl_init') && function_exists('curl_exec'); }
    static private function check_fopen() { return self::check_curl(); }
    static private function check_http() { return self::check_curl(); }
    static private function check_socket() { return self::check_curl(); }
}
