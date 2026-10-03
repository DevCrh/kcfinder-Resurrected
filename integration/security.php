<?php

namespace kcfinder;

/** Remove any previously granted KCFinder authorization from the session. */
function revoke_access()
{
    if (!isset($_SESSION) || !is_array($_SESSION)) {
        $_SESSION = array();
    }

    $_SESSION['KCFINDER'] = array('disabled' => true);
    unset($_SESSION['kcCsrf']);
}
