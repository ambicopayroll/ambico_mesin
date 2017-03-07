<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(61, "mi_dashboard_php", $Language->MenuPhrase("61", "MenuText"), "dashboard.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}dashboard.php'), FALSE, TRUE);
$RootMenu->AddMenuItem(1, "mi_att_log", $Language->MenuPhrase("1", "MenuText"), "att_loglist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}att_log'), FALSE, FALSE);
$RootMenu->AddMenuItem(2, "mi_cuti_normatif", $Language->MenuPhrase("2", "MenuText"), "cuti_normatiflist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}cuti_normatif'), FALSE, FALSE);
$RootMenu->AddMenuItem(3, "mi_dev_type", $Language->MenuPhrase("3", "MenuText"), "dev_typelist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}dev_type'), FALSE, FALSE);
$RootMenu->AddMenuItem(4, "mi_device", $Language->MenuPhrase("4", "MenuText"), "devicelist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}device'), FALSE, FALSE);
$RootMenu->AddMenuItem(5, "mi_ganti_jdw_d", $Language->MenuPhrase("5", "MenuText"), "ganti_jdw_dlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}ganti_jdw_d'), FALSE, FALSE);
$RootMenu->AddMenuItem(6, "mi_ganti_jdw_jk", $Language->MenuPhrase("6", "MenuText"), "ganti_jdw_jklist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}ganti_jdw_jk'), FALSE, FALSE);
$RootMenu->AddMenuItem(7, "mi_ganti_jdw_pegawai", $Language->MenuPhrase("7", "MenuText"), "ganti_jdw_pegawailist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}ganti_jdw_pegawai'), FALSE, FALSE);
$RootMenu->AddMenuItem(8, "mi_ganti_jdw_pembagian", $Language->MenuPhrase("8", "MenuText"), "ganti_jdw_pembagianlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}ganti_jdw_pembagian'), FALSE, FALSE);
$RootMenu->AddMenuItem(9, "mi_ganti_jk", $Language->MenuPhrase("9", "MenuText"), "ganti_jklist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}ganti_jk'), FALSE, FALSE);
$RootMenu->AddMenuItem(10, "mi_ganti_jk_d", $Language->MenuPhrase("10", "MenuText"), "ganti_jk_dlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}ganti_jk_d'), FALSE, FALSE);
$RootMenu->AddMenuItem(11, "mi_ganti_jk_pegawai", $Language->MenuPhrase("11", "MenuText"), "ganti_jk_pegawailist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}ganti_jk_pegawai'), FALSE, FALSE);
$RootMenu->AddMenuItem(12, "mi_ganti_jk_pembagian", $Language->MenuPhrase("12", "MenuText"), "ganti_jk_pembagianlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}ganti_jk_pembagian'), FALSE, FALSE);
$RootMenu->AddMenuItem(13, "mi_grp_user_d", $Language->MenuPhrase("13", "MenuText"), "grp_user_dlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}grp_user_d'), FALSE, FALSE);
$RootMenu->AddMenuItem(14, "mi_grp_user_m", $Language->MenuPhrase("14", "MenuText"), "grp_user_mlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}grp_user_m'), FALSE, FALSE);
$RootMenu->AddMenuItem(15, "mi_index_ot", $Language->MenuPhrase("15", "MenuText"), "index_otlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}index_ot'), FALSE, FALSE);
$RootMenu->AddMenuItem(16, "mi_index_type", $Language->MenuPhrase("16", "MenuText"), "index_typelist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}index_type'), FALSE, FALSE);
$RootMenu->AddMenuItem(17, "mi_izin", $Language->MenuPhrase("17", "MenuText"), "izinlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}izin'), FALSE, FALSE);
$RootMenu->AddMenuItem(18, "mi_jam_kerja", $Language->MenuPhrase("18", "MenuText"), "jam_kerjalist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}jam_kerja'), FALSE, FALSE);
$RootMenu->AddMenuItem(19, "mi_jam_kerja_extra", $Language->MenuPhrase("19", "MenuText"), "jam_kerja_extralist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}jam_kerja_extra'), FALSE, FALSE);
$RootMenu->AddMenuItem(20, "mi_jatah_cuti", $Language->MenuPhrase("20", "MenuText"), "jatah_cutilist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}jatah_cuti'), FALSE, FALSE);
$RootMenu->AddMenuItem(21, "mi_jdw_kerja_d", $Language->MenuPhrase("21", "MenuText"), "jdw_kerja_dlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}jdw_kerja_d'), FALSE, FALSE);
$RootMenu->AddMenuItem(22, "mi_jdw_kerja_m", $Language->MenuPhrase("22", "MenuText"), "jdw_kerja_mlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}jdw_kerja_m'), FALSE, FALSE);
$RootMenu->AddMenuItem(23, "mi_jdw_kerja_pegawai", $Language->MenuPhrase("23", "MenuText"), "jdw_kerja_pegawailist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}jdw_kerja_pegawai'), FALSE, FALSE);
$RootMenu->AddMenuItem(24, "mi_jns_izin", $Language->MenuPhrase("24", "MenuText"), "jns_izinlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}jns_izin'), FALSE, FALSE);
$RootMenu->AddMenuItem(25, "mi_kategori_izin", $Language->MenuPhrase("25", "MenuText"), "kategori_izinlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}kategori_izin'), FALSE, FALSE);
$RootMenu->AddMenuItem(26, "mi_kontrak_kerja", $Language->MenuPhrase("26", "MenuText"), "kontrak_kerjalist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}kontrak_kerja'), FALSE, FALSE);
$RootMenu->AddMenuItem(27, "mi_lembur", $Language->MenuPhrase("27", "MenuText"), "lemburlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}lembur'), FALSE, FALSE);
$RootMenu->AddMenuItem(28, "mi_libur", $Language->MenuPhrase("28", "MenuText"), "liburlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}libur'), FALSE, FALSE);
$RootMenu->AddMenuItem(29, "mi_pegawai", $Language->MenuPhrase("29", "MenuText"), "pegawailist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}pegawai'), FALSE, FALSE);
$RootMenu->AddMenuItem(30, "mi_pegawai_d", $Language->MenuPhrase("30", "MenuText"), "pegawai_dlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}pegawai_d'), FALSE, FALSE);
$RootMenu->AddMenuItem(31, "mi_pembagian1", $Language->MenuPhrase("31", "MenuText"), "pembagian1list.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}pembagian1'), FALSE, FALSE);
$RootMenu->AddMenuItem(32, "mi_pembagian2", $Language->MenuPhrase("32", "MenuText"), "pembagian2list.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}pembagian2'), FALSE, FALSE);
$RootMenu->AddMenuItem(33, "mi_pembagian3", $Language->MenuPhrase("33", "MenuText"), "pembagian3list.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}pembagian3'), FALSE, FALSE);
$RootMenu->AddMenuItem(34, "mi_pendidikan", $Language->MenuPhrase("34", "MenuText"), "pendidikanlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}pendidikan'), FALSE, FALSE);
$RootMenu->AddMenuItem(35, "mi_setting", $Language->MenuPhrase("35", "MenuText"), "settinglist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}setting'), FALSE, FALSE);
$RootMenu->AddMenuItem(36, "mi_shift_result", $Language->MenuPhrase("36", "MenuText"), "shift_resultlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}shift_result'), FALSE, FALSE);
$RootMenu->AddMenuItem(37, "mi_sms_group", $Language->MenuPhrase("37", "MenuText"), "sms_grouplist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}sms_group'), FALSE, FALSE);
$RootMenu->AddMenuItem(38, "mi_sms_group_member", $Language->MenuPhrase("38", "MenuText"), "sms_group_memberlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}sms_group_member'), FALSE, FALSE);
$RootMenu->AddMenuItem(39, "mi_sms_recipient", $Language->MenuPhrase("39", "MenuText"), "sms_recipientlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}sms_recipient'), FALSE, FALSE);
$RootMenu->AddMenuItem(40, "mi_temp_pegawai", $Language->MenuPhrase("40", "MenuText"), "temp_pegawailist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}temp_pegawai'), FALSE, FALSE);
$RootMenu->AddMenuItem(41, "mi_tmp", $Language->MenuPhrase("41", "MenuText"), "tmplist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}tmp'), FALSE, FALSE);
$RootMenu->AddMenuItem(42, "mi_tmp_uareu", $Language->MenuPhrase("42", "MenuText"), "tmp_uareulist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}tmp_uareu'), FALSE, FALSE);
$RootMenu->AddMenuItem(43, "mi_tukar_jam", $Language->MenuPhrase("43", "MenuText"), "tukar_jamlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}tukar_jam'), FALSE, FALSE);
$RootMenu->AddMenuItem(44, "mi_uareu_device", $Language->MenuPhrase("44", "MenuText"), "uareu_devicelist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}uareu_device'), FALSE, FALSE);
$RootMenu->AddMenuItem(45, "mi_user_log", $Language->MenuPhrase("45", "MenuText"), "user_loglist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}user_log'), FALSE, FALSE);
$RootMenu->AddMenuItem(46, "mi_user_login", $Language->MenuPhrase("46", "MenuText"), "user_loginlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}user_login'), FALSE, FALSE);
$RootMenu->AddMenuItem(47, "mi_versi_db", $Language->MenuPhrase("47", "MenuText"), "versi_dblist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}versi_db'), FALSE, FALSE);
$RootMenu->AddMenuItem(48, "mi_z_pay_com", $Language->MenuPhrase("48", "MenuText"), "z_pay_comlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}z_pay_com'), FALSE, FALSE);
$RootMenu->AddMenuItem(49, "mi_z_pay_grp", $Language->MenuPhrase("49", "MenuText"), "z_pay_grplist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}z_pay_grp'), FALSE, FALSE);
$RootMenu->AddMenuItem(50, "mi_z_pay_grp_com", $Language->MenuPhrase("50", "MenuText"), "z_pay_grp_comlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}z_pay_grp_com'), FALSE, FALSE);
$RootMenu->AddMenuItem(51, "mi_z_pay_grp_emp", $Language->MenuPhrase("51", "MenuText"), "z_pay_grp_emplist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}z_pay_grp_emp'), FALSE, FALSE);
$RootMenu->AddMenuItem(52, "mi_z_pay_money", $Language->MenuPhrase("52", "MenuText"), "z_pay_moneylist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}z_pay_money'), FALSE, FALSE);
$RootMenu->AddMenuItem(53, "mi_z_pay_process_d", $Language->MenuPhrase("53", "MenuText"), "z_pay_process_dlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}z_pay_process_d'), FALSE, FALSE);
$RootMenu->AddMenuItem(54, "mi_z_pay_process_m", $Language->MenuPhrase("54", "MenuText"), "z_pay_process_mlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}z_pay_process_m'), FALSE, FALSE);
$RootMenu->AddMenuItem(55, "mi_z_pay_process_sd", $Language->MenuPhrase("55", "MenuText"), "z_pay_process_sdlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}z_pay_process_sd'), FALSE, FALSE);
$RootMenu->AddMenuItem(56, "mi_z_pay_report", $Language->MenuPhrase("56", "MenuText"), "z_pay_reportlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}z_pay_report'), FALSE, FALSE);
$RootMenu->AddMenuItem(57, "mi_zx_bayar_kredit", $Language->MenuPhrase("57", "MenuText"), "zx_bayar_kreditlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}zx_bayar_kredit'), FALSE, FALSE);
$RootMenu->AddMenuItem(58, "mi_zx_jns_krd", $Language->MenuPhrase("58", "MenuText"), "zx_jns_krdlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}zx_jns_krd'), FALSE, FALSE);
$RootMenu->AddMenuItem(59, "mi_zx_kredit_d", $Language->MenuPhrase("59", "MenuText"), "zx_kredit_dlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}zx_kredit_d'), FALSE, FALSE);
$RootMenu->AddMenuItem(60, "mi_zx_kredit_m", $Language->MenuPhrase("60", "MenuText"), "zx_kredit_mlist.php", -1, "", AllowListMenu('{F36F5C9B-7A33-450D-8126-2253575B79B0}zx_kredit_m'), FALSE, FALSE);
$RootMenu->AddMenuItem(-2, "mi_changepwd", $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
