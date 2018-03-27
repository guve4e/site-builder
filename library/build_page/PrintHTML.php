<?php

/**
 *
 */
final class PrintHTML
{
    /**
     * Handles redirection (navigation)
     * trough the left side menu.
     * Constructs hrefs.
     *
     *
     * @param $item: object extracted from json file
     *         Each item has title, icon and id.
     * @param $viewName: string, the name of the view
     */
    public static function printOneLink($item, $viewName)
    {

        $title = $item['title']; // extract title
        $icon = $item['icon'];   // extract icon
        $id = $item['id'];       // extract id

        // set the link
        $alink = "?page=" . $id;

        // construct <li></li>
        echo "<li";

        if ($viewName == $id) {
            echo ' class="current_section"';
        }

        // check if $item has sub-item
        if (!isset($item['menu'])) // if not, set the title
        {
            echo " title='{$title}'";
        }

        print ">\n";
        // end <li>

        // print the hrefs
        print "    <a href='{$alink}'>\n";
        print "        <span class='menu_icon'><i class='material-icons'>&#x{$icon};</i></span>\n";
        print "        <span class='menu_title'>{$title}</span>\n";
        print "    </a>\n";

        // if the link has sub-menu
        if (isset($item['menu'])) {
            print "<ul>\n"; // sub-menu

            // $item['menu'] is a sub array
            // iterate troug it and structure <li></li>
            foreach ($item['menu'] as $sub_item) {

                $pid = $sub_item['id'];
                $alink = "?page=" . $pid;

                // start <li></li>
                print "<li";

                // check if the view name is the same as the id
                if ($viewName == $pid) // if so make it highlighted
                {
                    echo ' class="act_item"';
                }
                // make it a clickable link
                print "><a href='{$alink}'>" . $sub_item['title'] . "</a></li>\n";
            }
            print "</ul>\n";
        }
        print "</li>\n";
        // end <li>
    }

    /**
     * Print Page Styles
     */
    public static function printListStyles($csslist)
    {
        global $config;

        $stylesheet = "stylesheet";
        if (count($csslist) > 0) {
            print "    <!-- Print Styles -->\n";
            foreach ($csslist as $cssref) {
                print "    <link rel='{$cssref['rel']}' href='";
                if ($cssref['src'] == "bower") print $config['bower_url'];
                if (isset($cssref['path'])) echo $cssref['path'];
                if (isset($cssref['name'])) echo $cssref['name'];
                if (isset($cssref['min']) && $cssref['min'] == true) echo '.min';
                echo ".css' ";
                if (isset($cssref['media'])) print "media='{$cssref['media']}'";
                print ">\n";
            }
        }
    }

    /**
     * Print Page Scripts
     */
    public static function printListScripts($jslist)
    {
        global $config;

        if (count($jslist) > 0) {
            print "    <!-- Java Script -->\n";
            foreach ($jslist as $jsref) {
                echo "    <script src='";
                if ($jsref['src'] == "bower") print $config['bower_url'];
                if (isset($jsref['path'])) echo $jsref['path'];
                if (isset($jsref['name'])) echo $jsref['name'];
                if (isset($jsref['min']) && $jsref['min']) echo '.min';
                print ".js'></script>\n";
            }
        }
    }

    /**
     * Print Display Error
     */
    public static function displayErrorPage($msg)
    {
        print "<div id='page_content'>\n";
        print "    <div id='page_content_inner'>\n";
        print "        <div class='uk-alert uk-alert-danger'>{$msg}</div>\n";
        print "    </div>\n";
        print "</div>\n";
    }
}