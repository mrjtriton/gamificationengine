<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(1, "mi_aktivitas", $Language->MenuPhrase("1", "MenuText"), "helper_aktivitaslist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(2, "mi_aktivitas_badge", $Language->MenuPhrase("2", "MenuText"), "helper_aktivitas_badgelist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(3, "mi_badge", $Language->MenuPhrase("3", "MenuText"), "helper_badgelist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(4, "mi_user", $Language->MenuPhrase("4", "MenuText"), "helper_userlist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(5, "mi_user_aktivitas", $Language->MenuPhrase("5", "MenuText"), "helper_user_aktivitaslist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(6, "mi_user_badge", $Language->MenuPhrase("6", "MenuText"), "helper_user_badgelist.php", -1, "", TRUE, FALSE);
$RootMenu->Render();
?>
<!-- End Main Menu -->
