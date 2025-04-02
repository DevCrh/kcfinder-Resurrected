<!DOCTYPE html>
<html>

<head>
    <title>KCFinder: /<?= $this->session['dir'] ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
    <?php include "tpl/tpl_css.php" ?>
    <?php include "tpl/tpl_javascript.php" ?>
</head>

<body>
    <div id="resizer"></div>
    <div id="menu"></div>
    <div id="clipboard"></div>
    <div id="all">

        <div id="left">
            <div id="folders"></div>
        </div>

        <div id="right">

            <div id="toolbar">
                <div>
                    <a href="kcact:upload"><span><?= $this->label("Upload") ?></span></a>
                    <a href="kcact:refresh"><span><?= $this->label("Refresh") ?></span></a>
                    <a href="kcact:settings"><span><?= $this->label("Settings") ?></span></a>
                    <a href="kcact:maximize"><span><?= $this->label("Maximize") ?></span></a>
                    <a href="kcact:about"><span><?= $this->label("About") ?></span></a>
                    <div id="loading"></div>
                </div>
            </div>

            <div id="settings">

                <div>
                    <fieldset>
                        <legend><?= $this->label("View:") ?></legend>
                        <table summary="view" id="view">
                            <tr>
                                <th><input id="viewThumbs" type="radio" name="view" value="thumbs" /></th>
                                <td><label for="viewThumbs">&nbsp;<?= $this->label("Thumbnails") ?></label> &nbsp;</td>
                                <th><input id="viewList" type="radio" name="view" value="list" /></th>
                                <td><label for="viewList">&nbsp;<?= $this->label("List") ?></label></td>
                            </tr>
                        </table>
                    </fieldset>
                </div>

                <div>
                    <fieldset>
                        <legend><?= $this->label("Show:") ?></legend>
                        <table summary="show" id="show">
                            <tr>
                                <th><input id="showName" type="checkbox" name="name" /></th>
                                <td><label for="showName">&nbsp;<?= $this->label("Name") ?></label> &nbsp;</td>
                                <th><input id="showSize" type="checkbox" name="size" /></th>
                                <td><label for="showSize">&nbsp;<?= $this->label("Size") ?></label> &nbsp;</td>
                                <th><input id="showDimensions" type="checkbox" name="dimensions" /></th>
                                <td><label for="showDimensions">&nbsp;<?= $this->label("Dimensions") ?></label> &nbsp;</td>
                                <th><input id="showTime" type="checkbox" name="time" /></th>
                                <td><label for="showTime">&nbsp;<?= $this->label("Date") ?></label></td>
                            </tr>
                        </table>
                    </fieldset>
                </div>

                <div>
                    <fieldset>
                        <legend><?= $this->label("Order by:") ?></legend>
                        <table summary="order" id="order">
                            <tr>
                                <th><input id="sortName" type="radio" name="sort" value="name" /></th>
                                <td><label for="sortName">&nbsp;<?= $this->label("Name") ?></label> &nbsp;</td>
                                <th><input id="sortType" type="radio" name="sort" value="type" /></th>
                                <td><label for="sortType">&nbsp;<?= $this->label("Type") ?></label> &nbsp;</td>
                                <th><input id="sortSize" type="radio" name="sort" value="size" /></th>
                                <td><label for="sortSize">&nbsp;<?= $this->label("Size") ?></label> &nbsp;</td>
                                <th><input id="sortTime" type="radio" name="sort" value="date" /></th>
                                <td><label for="sortTime">&nbsp;<?= $this->label("Date") ?></label> &nbsp;</td>
                                <th><input id="sortOrder" type="checkbox" name="desc" /></th>
                                <td><label for="sortOrder">&nbsp;<?= $this->label("Descending") ?></label></td>
                            </tr>
                        </table>
                    </fieldset>
                </div>

                <div>
                    <select id="lang"></select>
                </div>

            </div>

            <div id="files">
                <div id="content"></div>
            </div>
        </div>
        <div id="status"><span id="fileinfo">&nbsp;</span></div>
    </div>
</body>

</html>