/** 
 *   @desc Document
 *   @package kcfinder-Resurrected
 *   @version 4.0
 *   @license http://opensource.org/licenses/GPL-3.0 GPLv3
 *   @license http://opensource.org/licenses/LGPL-3.0 LGPLv3
 */

_.viewOffice = function (file) {
    _.viewOfficeDialog(file, {
            title: 'Office Viewer',
            button: 'Close',
        }
    );
    return false;
};