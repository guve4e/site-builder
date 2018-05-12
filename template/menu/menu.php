
<aside>
    <h3>Side Menu</h3>
    <div class="sidenav">
        <ul>
            <?php
            // iterate trough each item and display it
            foreach ($this->menuConfig as $m) {
                PrintHTML::printOneMenuLink($m, $this->viewName);
            }
            ?>
        </ul>
    </div>
</aside>