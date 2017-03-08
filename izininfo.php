<?php

// Global variable for table object
$izin = NULL;

//
// Table class for izin
//
class cizin extends cTable {
	var $pegawai_id;
	var $izin_urutan;
	var $izin_tgl_pengajuan;
	var $izin_tgl;
	var $izin_jenis_id;
	var $izin_catatan;
	var $izin_status;
	var $izin_tinggal_t1;
	var $izin_tinggal_t2;
	var $cuti_n_id;
	var $izin_ket_lain;
	var $izin_noscan_time;
	var $kat_izin_id;
	var $ket_status;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'izin';
		$this->TableName = 'izin';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`izin`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// pegawai_id
		$this->pegawai_id = new cField('izin', 'izin', 'x_pegawai_id', 'pegawai_id', '`pegawai_id`', '`pegawai_id`', 3, -1, FALSE, '`pegawai_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pegawai_id->Sortable = TRUE; // Allow sort
		$this->pegawai_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['pegawai_id'] = &$this->pegawai_id;

		// izin_urutan
		$this->izin_urutan = new cField('izin', 'izin', 'x_izin_urutan', 'izin_urutan', '`izin_urutan`', '`izin_urutan`', 2, -1, FALSE, '`izin_urutan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->izin_urutan->Sortable = TRUE; // Allow sort
		$this->izin_urutan->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['izin_urutan'] = &$this->izin_urutan;

		// izin_tgl_pengajuan
		$this->izin_tgl_pengajuan = new cField('izin', 'izin', 'x_izin_tgl_pengajuan', 'izin_tgl_pengajuan', '`izin_tgl_pengajuan`', ew_CastDateFieldForLike('`izin_tgl_pengajuan`', 0, "DB"), 133, 0, FALSE, '`izin_tgl_pengajuan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->izin_tgl_pengajuan->Sortable = TRUE; // Allow sort
		$this->izin_tgl_pengajuan->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['izin_tgl_pengajuan'] = &$this->izin_tgl_pengajuan;

		// izin_tgl
		$this->izin_tgl = new cField('izin', 'izin', 'x_izin_tgl', 'izin_tgl', '`izin_tgl`', ew_CastDateFieldForLike('`izin_tgl`', 0, "DB"), 133, 0, FALSE, '`izin_tgl`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->izin_tgl->Sortable = TRUE; // Allow sort
		$this->izin_tgl->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['izin_tgl'] = &$this->izin_tgl;

		// izin_jenis_id
		$this->izin_jenis_id = new cField('izin', 'izin', 'x_izin_jenis_id', 'izin_jenis_id', '`izin_jenis_id`', '`izin_jenis_id`', 2, -1, FALSE, '`izin_jenis_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->izin_jenis_id->Sortable = TRUE; // Allow sort
		$this->izin_jenis_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['izin_jenis_id'] = &$this->izin_jenis_id;

		// izin_catatan
		$this->izin_catatan = new cField('izin', 'izin', 'x_izin_catatan', 'izin_catatan', '`izin_catatan`', '`izin_catatan`', 200, -1, FALSE, '`izin_catatan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->izin_catatan->Sortable = TRUE; // Allow sort
		$this->fields['izin_catatan'] = &$this->izin_catatan;

		// izin_status
		$this->izin_status = new cField('izin', 'izin', 'x_izin_status', 'izin_status', '`izin_status`', '`izin_status`', 16, -1, FALSE, '`izin_status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->izin_status->Sortable = TRUE; // Allow sort
		$this->izin_status->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['izin_status'] = &$this->izin_status;

		// izin_tinggal_t1
		$this->izin_tinggal_t1 = new cField('izin', 'izin', 'x_izin_tinggal_t1', 'izin_tinggal_t1', '`izin_tinggal_t1`', ew_CastDateFieldForLike('`izin_tinggal_t1`', 0, "DB"), 134, -1, FALSE, '`izin_tinggal_t1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->izin_tinggal_t1->Sortable = TRUE; // Allow sort
		$this->izin_tinggal_t1->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_TIME_SEPARATOR"], $Language->Phrase("IncorrectTime"));
		$this->fields['izin_tinggal_t1'] = &$this->izin_tinggal_t1;

		// izin_tinggal_t2
		$this->izin_tinggal_t2 = new cField('izin', 'izin', 'x_izin_tinggal_t2', 'izin_tinggal_t2', '`izin_tinggal_t2`', ew_CastDateFieldForLike('`izin_tinggal_t2`', 0, "DB"), 134, -1, FALSE, '`izin_tinggal_t2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->izin_tinggal_t2->Sortable = TRUE; // Allow sort
		$this->izin_tinggal_t2->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_TIME_SEPARATOR"], $Language->Phrase("IncorrectTime"));
		$this->fields['izin_tinggal_t2'] = &$this->izin_tinggal_t2;

		// cuti_n_id
		$this->cuti_n_id = new cField('izin', 'izin', 'x_cuti_n_id', 'cuti_n_id', '`cuti_n_id`', '`cuti_n_id`', 3, -1, FALSE, '`cuti_n_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->cuti_n_id->Sortable = TRUE; // Allow sort
		$this->cuti_n_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cuti_n_id'] = &$this->cuti_n_id;

		// izin_ket_lain
		$this->izin_ket_lain = new cField('izin', 'izin', 'x_izin_ket_lain', 'izin_ket_lain', '`izin_ket_lain`', '`izin_ket_lain`', 200, -1, FALSE, '`izin_ket_lain`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->izin_ket_lain->Sortable = TRUE; // Allow sort
		$this->fields['izin_ket_lain'] = &$this->izin_ket_lain;

		// izin_noscan_time
		$this->izin_noscan_time = new cField('izin', 'izin', 'x_izin_noscan_time', 'izin_noscan_time', '`izin_noscan_time`', ew_CastDateFieldForLike('`izin_noscan_time`', 0, "DB"), 134, -1, FALSE, '`izin_noscan_time`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->izin_noscan_time->Sortable = TRUE; // Allow sort
		$this->izin_noscan_time->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_TIME_SEPARATOR"], $Language->Phrase("IncorrectTime"));
		$this->fields['izin_noscan_time'] = &$this->izin_noscan_time;

		// kat_izin_id
		$this->kat_izin_id = new cField('izin', 'izin', 'x_kat_izin_id', 'kat_izin_id', '`kat_izin_id`', '`kat_izin_id`', 3, -1, FALSE, '`kat_izin_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->kat_izin_id->Sortable = TRUE; // Allow sort
		$this->kat_izin_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['kat_izin_id'] = &$this->kat_izin_id;

		// ket_status
		$this->ket_status = new cField('izin', 'izin', 'x_ket_status', 'ket_status', '`ket_status`', '`ket_status`', 200, -1, FALSE, '`ket_status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->ket_status->Sortable = TRUE; // Allow sort
		$this->fields['ket_status'] = &$this->ket_status;
	}

	// Set Field Visibility
	function SetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Multiple column sort
	function UpdateSort(&$ofld, $ctrl) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			if ($ctrl) {
				$sOrderBy = $this->getSessionOrderBy();
				if (strpos($sOrderBy, $sSortField . " " . $sLastSort) !== FALSE) {
					$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
				} else {
					if ($sOrderBy <> "") $sOrderBy .= ", ";
					$sOrderBy .= $sSortField . " " . $sThisSort;
				}
				$this->setSessionOrderBy($sOrderBy); // Save to Session
			} else {
				$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
			}
		} else {
			if (!$ctrl) $ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`izin`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('pegawai_id', $rs))
				ew_AddFilter($where, ew_QuotedName('pegawai_id', $this->DBID) . '=' . ew_QuotedValue($rs['pegawai_id'], $this->pegawai_id->FldDataType, $this->DBID));
			if (array_key_exists('izin_urutan', $rs))
				ew_AddFilter($where, ew_QuotedName('izin_urutan', $this->DBID) . '=' . ew_QuotedValue($rs['izin_urutan'], $this->izin_urutan->FldDataType, $this->DBID));
			if (array_key_exists('izin_tgl', $rs))
				ew_AddFilter($where, ew_QuotedName('izin_tgl', $this->DBID) . '=' . ew_QuotedValue($rs['izin_tgl'], $this->izin_tgl->FldDataType, $this->DBID));
			if (array_key_exists('izin_jenis_id', $rs))
				ew_AddFilter($where, ew_QuotedName('izin_jenis_id', $this->DBID) . '=' . ew_QuotedValue($rs['izin_jenis_id'], $this->izin_jenis_id->FldDataType, $this->DBID));
			if (array_key_exists('izin_status', $rs))
				ew_AddFilter($where, ew_QuotedName('izin_status', $this->DBID) . '=' . ew_QuotedValue($rs['izin_status'], $this->izin_status->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`pegawai_id` = @pegawai_id@ AND `izin_urutan` = @izin_urutan@ AND `izin_tgl` = '@izin_tgl@' AND `izin_jenis_id` = @izin_jenis_id@ AND `izin_status` = @izin_status@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->pegawai_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@pegawai_id@", ew_AdjustSql($this->pegawai_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		if (!is_numeric($this->izin_urutan->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@izin_urutan@", ew_AdjustSql($this->izin_urutan->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		$sKeyFilter = str_replace("@izin_tgl@", ew_AdjustSql(ew_UnFormatDateTime($this->izin_tgl->CurrentValue,0), $this->DBID), $sKeyFilter); // Replace key value
		if (!is_numeric($this->izin_jenis_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@izin_jenis_id@", ew_AdjustSql($this->izin_jenis_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		if (!is_numeric($this->izin_status->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@izin_status@", ew_AdjustSql($this->izin_status->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "izinlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "izinlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("izinview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("izinview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "izinadd.php?" . $this->UrlParm($parm);
		else
			$url = "izinadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("izinedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("izinadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("izindelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "pegawai_id:" . ew_VarToJson($this->pegawai_id->CurrentValue, "number", "'");
		$json .= ",izin_urutan:" . ew_VarToJson($this->izin_urutan->CurrentValue, "number", "'");
		$json .= ",izin_tgl:" . ew_VarToJson($this->izin_tgl->CurrentValue, "string", "'");
		$json .= ",izin_jenis_id:" . ew_VarToJson($this->izin_jenis_id->CurrentValue, "number", "'");
		$json .= ",izin_status:" . ew_VarToJson($this->izin_status->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->pegawai_id->CurrentValue)) {
			$sUrl .= "pegawai_id=" . urlencode($this->pegawai_id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->izin_urutan->CurrentValue)) {
			$sUrl .= "&izin_urutan=" . urlencode($this->izin_urutan->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->izin_tgl->CurrentValue)) {
			$sUrl .= "&izin_tgl=" . urlencode($this->izin_tgl->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->izin_jenis_id->CurrentValue)) {
			$sUrl .= "&izin_jenis_id=" . urlencode($this->izin_jenis_id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->izin_status->CurrentValue)) {
			$sUrl .= "&izin_status=" . urlencode($this->izin_status->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["pegawai_id"]))
				$arKey[] = ew_StripSlashes($_POST["pegawai_id"]);
			elseif (isset($_GET["pegawai_id"]))
				$arKey[] = ew_StripSlashes($_GET["pegawai_id"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["izin_urutan"]))
				$arKey[] = ew_StripSlashes($_POST["izin_urutan"]);
			elseif (isset($_GET["izin_urutan"]))
				$arKey[] = ew_StripSlashes($_GET["izin_urutan"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["izin_tgl"]))
				$arKey[] = ew_StripSlashes($_POST["izin_tgl"]);
			elseif (isset($_GET["izin_tgl"]))
				$arKey[] = ew_StripSlashes($_GET["izin_tgl"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["izin_jenis_id"]))
				$arKey[] = ew_StripSlashes($_POST["izin_jenis_id"]);
			elseif (isset($_GET["izin_jenis_id"]))
				$arKey[] = ew_StripSlashes($_GET["izin_jenis_id"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["izin_status"]))
				$arKey[] = ew_StripSlashes($_POST["izin_status"]);
			elseif (isset($_GET["izin_status"]))
				$arKey[] = ew_StripSlashes($_GET["izin_status"]);
			else
				$arKeys = NULL; // Do not setup
			if (is_array($arKeys)) $arKeys[] = $arKey;

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_array($key) || count($key) <> 5)
					continue; // Just skip so other keys will still work
				if (!is_numeric($key[0])) // pegawai_id
					continue;
				if (!is_numeric($key[1])) // izin_urutan
					continue;
				if (!is_numeric($key[3])) // izin_jenis_id
					continue;
				if (!is_numeric($key[4])) // izin_status
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->pegawai_id->CurrentValue = $key[0];
			$this->izin_urutan->CurrentValue = $key[1];
			$this->izin_tgl->CurrentValue = $key[2];
			$this->izin_jenis_id->CurrentValue = $key[3];
			$this->izin_status->CurrentValue = $key[4];
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->pegawai_id->setDbValue($rs->fields('pegawai_id'));
		$this->izin_urutan->setDbValue($rs->fields('izin_urutan'));
		$this->izin_tgl_pengajuan->setDbValue($rs->fields('izin_tgl_pengajuan'));
		$this->izin_tgl->setDbValue($rs->fields('izin_tgl'));
		$this->izin_jenis_id->setDbValue($rs->fields('izin_jenis_id'));
		$this->izin_catatan->setDbValue($rs->fields('izin_catatan'));
		$this->izin_status->setDbValue($rs->fields('izin_status'));
		$this->izin_tinggal_t1->setDbValue($rs->fields('izin_tinggal_t1'));
		$this->izin_tinggal_t2->setDbValue($rs->fields('izin_tinggal_t2'));
		$this->cuti_n_id->setDbValue($rs->fields('cuti_n_id'));
		$this->izin_ket_lain->setDbValue($rs->fields('izin_ket_lain'));
		$this->izin_noscan_time->setDbValue($rs->fields('izin_noscan_time'));
		$this->kat_izin_id->setDbValue($rs->fields('kat_izin_id'));
		$this->ket_status->setDbValue($rs->fields('ket_status'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// pegawai_id
		// izin_urutan
		// izin_tgl_pengajuan
		// izin_tgl
		// izin_jenis_id
		// izin_catatan
		// izin_status
		// izin_tinggal_t1
		// izin_tinggal_t2
		// cuti_n_id
		// izin_ket_lain
		// izin_noscan_time
		// kat_izin_id
		// ket_status
		// pegawai_id

		$this->pegawai_id->ViewValue = $this->pegawai_id->CurrentValue;
		$this->pegawai_id->ViewCustomAttributes = "";

		// izin_urutan
		$this->izin_urutan->ViewValue = $this->izin_urutan->CurrentValue;
		$this->izin_urutan->ViewCustomAttributes = "";

		// izin_tgl_pengajuan
		$this->izin_tgl_pengajuan->ViewValue = $this->izin_tgl_pengajuan->CurrentValue;
		$this->izin_tgl_pengajuan->ViewValue = ew_FormatDateTime($this->izin_tgl_pengajuan->ViewValue, 0);
		$this->izin_tgl_pengajuan->ViewCustomAttributes = "";

		// izin_tgl
		$this->izin_tgl->ViewValue = $this->izin_tgl->CurrentValue;
		$this->izin_tgl->ViewValue = ew_FormatDateTime($this->izin_tgl->ViewValue, 0);
		$this->izin_tgl->ViewCustomAttributes = "";

		// izin_jenis_id
		$this->izin_jenis_id->ViewValue = $this->izin_jenis_id->CurrentValue;
		$this->izin_jenis_id->ViewCustomAttributes = "";

		// izin_catatan
		$this->izin_catatan->ViewValue = $this->izin_catatan->CurrentValue;
		$this->izin_catatan->ViewCustomAttributes = "";

		// izin_status
		$this->izin_status->ViewValue = $this->izin_status->CurrentValue;
		$this->izin_status->ViewCustomAttributes = "";

		// izin_tinggal_t1
		$this->izin_tinggal_t1->ViewValue = $this->izin_tinggal_t1->CurrentValue;
		$this->izin_tinggal_t1->ViewCustomAttributes = "";

		// izin_tinggal_t2
		$this->izin_tinggal_t2->ViewValue = $this->izin_tinggal_t2->CurrentValue;
		$this->izin_tinggal_t2->ViewCustomAttributes = "";

		// cuti_n_id
		$this->cuti_n_id->ViewValue = $this->cuti_n_id->CurrentValue;
		$this->cuti_n_id->ViewCustomAttributes = "";

		// izin_ket_lain
		$this->izin_ket_lain->ViewValue = $this->izin_ket_lain->CurrentValue;
		$this->izin_ket_lain->ViewCustomAttributes = "";

		// izin_noscan_time
		$this->izin_noscan_time->ViewValue = $this->izin_noscan_time->CurrentValue;
		$this->izin_noscan_time->ViewCustomAttributes = "";

		// kat_izin_id
		$this->kat_izin_id->ViewValue = $this->kat_izin_id->CurrentValue;
		$this->kat_izin_id->ViewCustomAttributes = "";

		// ket_status
		$this->ket_status->ViewValue = $this->ket_status->CurrentValue;
		$this->ket_status->ViewCustomAttributes = "";

		// pegawai_id
		$this->pegawai_id->LinkCustomAttributes = "";
		$this->pegawai_id->HrefValue = "";
		$this->pegawai_id->TooltipValue = "";

		// izin_urutan
		$this->izin_urutan->LinkCustomAttributes = "";
		$this->izin_urutan->HrefValue = "";
		$this->izin_urutan->TooltipValue = "";

		// izin_tgl_pengajuan
		$this->izin_tgl_pengajuan->LinkCustomAttributes = "";
		$this->izin_tgl_pengajuan->HrefValue = "";
		$this->izin_tgl_pengajuan->TooltipValue = "";

		// izin_tgl
		$this->izin_tgl->LinkCustomAttributes = "";
		$this->izin_tgl->HrefValue = "";
		$this->izin_tgl->TooltipValue = "";

		// izin_jenis_id
		$this->izin_jenis_id->LinkCustomAttributes = "";
		$this->izin_jenis_id->HrefValue = "";
		$this->izin_jenis_id->TooltipValue = "";

		// izin_catatan
		$this->izin_catatan->LinkCustomAttributes = "";
		$this->izin_catatan->HrefValue = "";
		$this->izin_catatan->TooltipValue = "";

		// izin_status
		$this->izin_status->LinkCustomAttributes = "";
		$this->izin_status->HrefValue = "";
		$this->izin_status->TooltipValue = "";

		// izin_tinggal_t1
		$this->izin_tinggal_t1->LinkCustomAttributes = "";
		$this->izin_tinggal_t1->HrefValue = "";
		$this->izin_tinggal_t1->TooltipValue = "";

		// izin_tinggal_t2
		$this->izin_tinggal_t2->LinkCustomAttributes = "";
		$this->izin_tinggal_t2->HrefValue = "";
		$this->izin_tinggal_t2->TooltipValue = "";

		// cuti_n_id
		$this->cuti_n_id->LinkCustomAttributes = "";
		$this->cuti_n_id->HrefValue = "";
		$this->cuti_n_id->TooltipValue = "";

		// izin_ket_lain
		$this->izin_ket_lain->LinkCustomAttributes = "";
		$this->izin_ket_lain->HrefValue = "";
		$this->izin_ket_lain->TooltipValue = "";

		// izin_noscan_time
		$this->izin_noscan_time->LinkCustomAttributes = "";
		$this->izin_noscan_time->HrefValue = "";
		$this->izin_noscan_time->TooltipValue = "";

		// kat_izin_id
		$this->kat_izin_id->LinkCustomAttributes = "";
		$this->kat_izin_id->HrefValue = "";
		$this->kat_izin_id->TooltipValue = "";

		// ket_status
		$this->ket_status->LinkCustomAttributes = "";
		$this->ket_status->HrefValue = "";
		$this->ket_status->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// pegawai_id
		$this->pegawai_id->EditAttrs["class"] = "form-control";
		$this->pegawai_id->EditCustomAttributes = "";
		$this->pegawai_id->EditValue = $this->pegawai_id->CurrentValue;
		$this->pegawai_id->ViewCustomAttributes = "";

		// izin_urutan
		$this->izin_urutan->EditAttrs["class"] = "form-control";
		$this->izin_urutan->EditCustomAttributes = "";
		$this->izin_urutan->EditValue = $this->izin_urutan->CurrentValue;
		$this->izin_urutan->ViewCustomAttributes = "";

		// izin_tgl_pengajuan
		$this->izin_tgl_pengajuan->EditAttrs["class"] = "form-control";
		$this->izin_tgl_pengajuan->EditCustomAttributes = "";
		$this->izin_tgl_pengajuan->EditValue = ew_FormatDateTime($this->izin_tgl_pengajuan->CurrentValue, 8);
		$this->izin_tgl_pengajuan->PlaceHolder = ew_RemoveHtml($this->izin_tgl_pengajuan->FldCaption());

		// izin_tgl
		$this->izin_tgl->EditAttrs["class"] = "form-control";
		$this->izin_tgl->EditCustomAttributes = "";
		$this->izin_tgl->EditValue = $this->izin_tgl->CurrentValue;
		$this->izin_tgl->EditValue = ew_FormatDateTime($this->izin_tgl->EditValue, 0);
		$this->izin_tgl->ViewCustomAttributes = "";

		// izin_jenis_id
		$this->izin_jenis_id->EditAttrs["class"] = "form-control";
		$this->izin_jenis_id->EditCustomAttributes = "";
		$this->izin_jenis_id->EditValue = $this->izin_jenis_id->CurrentValue;
		$this->izin_jenis_id->ViewCustomAttributes = "";

		// izin_catatan
		$this->izin_catatan->EditAttrs["class"] = "form-control";
		$this->izin_catatan->EditCustomAttributes = "";
		$this->izin_catatan->EditValue = $this->izin_catatan->CurrentValue;
		$this->izin_catatan->PlaceHolder = ew_RemoveHtml($this->izin_catatan->FldCaption());

		// izin_status
		$this->izin_status->EditAttrs["class"] = "form-control";
		$this->izin_status->EditCustomAttributes = "";
		$this->izin_status->EditValue = $this->izin_status->CurrentValue;
		$this->izin_status->ViewCustomAttributes = "";

		// izin_tinggal_t1
		$this->izin_tinggal_t1->EditAttrs["class"] = "form-control";
		$this->izin_tinggal_t1->EditCustomAttributes = "";
		$this->izin_tinggal_t1->EditValue = $this->izin_tinggal_t1->CurrentValue;
		$this->izin_tinggal_t1->PlaceHolder = ew_RemoveHtml($this->izin_tinggal_t1->FldCaption());

		// izin_tinggal_t2
		$this->izin_tinggal_t2->EditAttrs["class"] = "form-control";
		$this->izin_tinggal_t2->EditCustomAttributes = "";
		$this->izin_tinggal_t2->EditValue = $this->izin_tinggal_t2->CurrentValue;
		$this->izin_tinggal_t2->PlaceHolder = ew_RemoveHtml($this->izin_tinggal_t2->FldCaption());

		// cuti_n_id
		$this->cuti_n_id->EditAttrs["class"] = "form-control";
		$this->cuti_n_id->EditCustomAttributes = "";
		$this->cuti_n_id->EditValue = $this->cuti_n_id->CurrentValue;
		$this->cuti_n_id->PlaceHolder = ew_RemoveHtml($this->cuti_n_id->FldCaption());

		// izin_ket_lain
		$this->izin_ket_lain->EditAttrs["class"] = "form-control";
		$this->izin_ket_lain->EditCustomAttributes = "";
		$this->izin_ket_lain->EditValue = $this->izin_ket_lain->CurrentValue;
		$this->izin_ket_lain->PlaceHolder = ew_RemoveHtml($this->izin_ket_lain->FldCaption());

		// izin_noscan_time
		$this->izin_noscan_time->EditAttrs["class"] = "form-control";
		$this->izin_noscan_time->EditCustomAttributes = "";
		$this->izin_noscan_time->EditValue = $this->izin_noscan_time->CurrentValue;
		$this->izin_noscan_time->PlaceHolder = ew_RemoveHtml($this->izin_noscan_time->FldCaption());

		// kat_izin_id
		$this->kat_izin_id->EditAttrs["class"] = "form-control";
		$this->kat_izin_id->EditCustomAttributes = "";
		$this->kat_izin_id->EditValue = $this->kat_izin_id->CurrentValue;
		$this->kat_izin_id->PlaceHolder = ew_RemoveHtml($this->kat_izin_id->FldCaption());

		// ket_status
		$this->ket_status->EditAttrs["class"] = "form-control";
		$this->ket_status->EditCustomAttributes = "";
		$this->ket_status->EditValue = $this->ket_status->CurrentValue;
		$this->ket_status->PlaceHolder = ew_RemoveHtml($this->ket_status->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->pegawai_id->Exportable) $Doc->ExportCaption($this->pegawai_id);
					if ($this->izin_urutan->Exportable) $Doc->ExportCaption($this->izin_urutan);
					if ($this->izin_tgl_pengajuan->Exportable) $Doc->ExportCaption($this->izin_tgl_pengajuan);
					if ($this->izin_tgl->Exportable) $Doc->ExportCaption($this->izin_tgl);
					if ($this->izin_jenis_id->Exportable) $Doc->ExportCaption($this->izin_jenis_id);
					if ($this->izin_catatan->Exportable) $Doc->ExportCaption($this->izin_catatan);
					if ($this->izin_status->Exportable) $Doc->ExportCaption($this->izin_status);
					if ($this->izin_tinggal_t1->Exportable) $Doc->ExportCaption($this->izin_tinggal_t1);
					if ($this->izin_tinggal_t2->Exportable) $Doc->ExportCaption($this->izin_tinggal_t2);
					if ($this->cuti_n_id->Exportable) $Doc->ExportCaption($this->cuti_n_id);
					if ($this->izin_ket_lain->Exportable) $Doc->ExportCaption($this->izin_ket_lain);
					if ($this->izin_noscan_time->Exportable) $Doc->ExportCaption($this->izin_noscan_time);
					if ($this->kat_izin_id->Exportable) $Doc->ExportCaption($this->kat_izin_id);
					if ($this->ket_status->Exportable) $Doc->ExportCaption($this->ket_status);
				} else {
					if ($this->pegawai_id->Exportable) $Doc->ExportCaption($this->pegawai_id);
					if ($this->izin_urutan->Exportable) $Doc->ExportCaption($this->izin_urutan);
					if ($this->izin_tgl_pengajuan->Exportable) $Doc->ExportCaption($this->izin_tgl_pengajuan);
					if ($this->izin_tgl->Exportable) $Doc->ExportCaption($this->izin_tgl);
					if ($this->izin_jenis_id->Exportable) $Doc->ExportCaption($this->izin_jenis_id);
					if ($this->izin_catatan->Exportable) $Doc->ExportCaption($this->izin_catatan);
					if ($this->izin_status->Exportable) $Doc->ExportCaption($this->izin_status);
					if ($this->izin_tinggal_t1->Exportable) $Doc->ExportCaption($this->izin_tinggal_t1);
					if ($this->izin_tinggal_t2->Exportable) $Doc->ExportCaption($this->izin_tinggal_t2);
					if ($this->cuti_n_id->Exportable) $Doc->ExportCaption($this->cuti_n_id);
					if ($this->izin_ket_lain->Exportable) $Doc->ExportCaption($this->izin_ket_lain);
					if ($this->izin_noscan_time->Exportable) $Doc->ExportCaption($this->izin_noscan_time);
					if ($this->kat_izin_id->Exportable) $Doc->ExportCaption($this->kat_izin_id);
					if ($this->ket_status->Exportable) $Doc->ExportCaption($this->ket_status);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->pegawai_id->Exportable) $Doc->ExportField($this->pegawai_id);
						if ($this->izin_urutan->Exportable) $Doc->ExportField($this->izin_urutan);
						if ($this->izin_tgl_pengajuan->Exportable) $Doc->ExportField($this->izin_tgl_pengajuan);
						if ($this->izin_tgl->Exportable) $Doc->ExportField($this->izin_tgl);
						if ($this->izin_jenis_id->Exportable) $Doc->ExportField($this->izin_jenis_id);
						if ($this->izin_catatan->Exportable) $Doc->ExportField($this->izin_catatan);
						if ($this->izin_status->Exportable) $Doc->ExportField($this->izin_status);
						if ($this->izin_tinggal_t1->Exportable) $Doc->ExportField($this->izin_tinggal_t1);
						if ($this->izin_tinggal_t2->Exportable) $Doc->ExportField($this->izin_tinggal_t2);
						if ($this->cuti_n_id->Exportable) $Doc->ExportField($this->cuti_n_id);
						if ($this->izin_ket_lain->Exportable) $Doc->ExportField($this->izin_ket_lain);
						if ($this->izin_noscan_time->Exportable) $Doc->ExportField($this->izin_noscan_time);
						if ($this->kat_izin_id->Exportable) $Doc->ExportField($this->kat_izin_id);
						if ($this->ket_status->Exportable) $Doc->ExportField($this->ket_status);
					} else {
						if ($this->pegawai_id->Exportable) $Doc->ExportField($this->pegawai_id);
						if ($this->izin_urutan->Exportable) $Doc->ExportField($this->izin_urutan);
						if ($this->izin_tgl_pengajuan->Exportable) $Doc->ExportField($this->izin_tgl_pengajuan);
						if ($this->izin_tgl->Exportable) $Doc->ExportField($this->izin_tgl);
						if ($this->izin_jenis_id->Exportable) $Doc->ExportField($this->izin_jenis_id);
						if ($this->izin_catatan->Exportable) $Doc->ExportField($this->izin_catatan);
						if ($this->izin_status->Exportable) $Doc->ExportField($this->izin_status);
						if ($this->izin_tinggal_t1->Exportable) $Doc->ExportField($this->izin_tinggal_t1);
						if ($this->izin_tinggal_t2->Exportable) $Doc->ExportField($this->izin_tinggal_t2);
						if ($this->cuti_n_id->Exportable) $Doc->ExportField($this->cuti_n_id);
						if ($this->izin_ket_lain->Exportable) $Doc->ExportField($this->izin_ket_lain);
						if ($this->izin_noscan_time->Exportable) $Doc->ExportField($this->izin_noscan_time);
						if ($this->kat_izin_id->Exportable) $Doc->ExportField($this->kat_izin_id);
						if ($this->ket_status->Exportable) $Doc->ExportField($this->ket_status);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
