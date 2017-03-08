<?php

// Global variable for table object
$lembur = NULL;

//
// Table class for lembur
//
class clembur extends cTable {
	var $pegawai_id;
	var $lembur_tgl;
	var $lembur_mulai;
	var $lembur_selesai;
	var $lembur_urut;
	var $type_ot;
	var $lembur_durasi_min;
	var $lembur_durasi_max;
	var $lembur_keperluan;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'lembur';
		$this->TableName = 'lembur';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`lembur`";
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
		$this->pegawai_id = new cField('lembur', 'lembur', 'x_pegawai_id', 'pegawai_id', '`pegawai_id`', '`pegawai_id`', 3, -1, FALSE, '`pegawai_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pegawai_id->Sortable = TRUE; // Allow sort
		$this->pegawai_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['pegawai_id'] = &$this->pegawai_id;

		// lembur_tgl
		$this->lembur_tgl = new cField('lembur', 'lembur', 'x_lembur_tgl', 'lembur_tgl', '`lembur_tgl`', ew_CastDateFieldForLike('`lembur_tgl`', 0, "DB"), 133, 0, FALSE, '`lembur_tgl`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->lembur_tgl->Sortable = TRUE; // Allow sort
		$this->lembur_tgl->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['lembur_tgl'] = &$this->lembur_tgl;

		// lembur_mulai
		$this->lembur_mulai = new cField('lembur', 'lembur', 'x_lembur_mulai', 'lembur_mulai', '`lembur_mulai`', ew_CastDateFieldForLike('`lembur_mulai`', 0, "DB"), 134, -1, FALSE, '`lembur_mulai`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->lembur_mulai->Sortable = TRUE; // Allow sort
		$this->lembur_mulai->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_TIME_SEPARATOR"], $Language->Phrase("IncorrectTime"));
		$this->fields['lembur_mulai'] = &$this->lembur_mulai;

		// lembur_selesai
		$this->lembur_selesai = new cField('lembur', 'lembur', 'x_lembur_selesai', 'lembur_selesai', '`lembur_selesai`', ew_CastDateFieldForLike('`lembur_selesai`', 0, "DB"), 134, -1, FALSE, '`lembur_selesai`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->lembur_selesai->Sortable = TRUE; // Allow sort
		$this->lembur_selesai->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_TIME_SEPARATOR"], $Language->Phrase("IncorrectTime"));
		$this->fields['lembur_selesai'] = &$this->lembur_selesai;

		// lembur_urut
		$this->lembur_urut = new cField('lembur', 'lembur', 'x_lembur_urut', 'lembur_urut', '`lembur_urut`', '`lembur_urut`', 16, -1, FALSE, '`lembur_urut`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->lembur_urut->Sortable = TRUE; // Allow sort
		$this->lembur_urut->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['lembur_urut'] = &$this->lembur_urut;

		// type_ot
		$this->type_ot = new cField('lembur', 'lembur', 'x_type_ot', 'type_ot', '`type_ot`', '`type_ot`', 16, -1, FALSE, '`type_ot`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->type_ot->Sortable = TRUE; // Allow sort
		$this->type_ot->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['type_ot'] = &$this->type_ot;

		// lembur_durasi_min
		$this->lembur_durasi_min = new cField('lembur', 'lembur', 'x_lembur_durasi_min', 'lembur_durasi_min', '`lembur_durasi_min`', '`lembur_durasi_min`', 2, -1, FALSE, '`lembur_durasi_min`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->lembur_durasi_min->Sortable = TRUE; // Allow sort
		$this->lembur_durasi_min->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['lembur_durasi_min'] = &$this->lembur_durasi_min;

		// lembur_durasi_max
		$this->lembur_durasi_max = new cField('lembur', 'lembur', 'x_lembur_durasi_max', 'lembur_durasi_max', '`lembur_durasi_max`', '`lembur_durasi_max`', 2, -1, FALSE, '`lembur_durasi_max`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->lembur_durasi_max->Sortable = TRUE; // Allow sort
		$this->lembur_durasi_max->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['lembur_durasi_max'] = &$this->lembur_durasi_max;

		// lembur_keperluan
		$this->lembur_keperluan = new cField('lembur', 'lembur', 'x_lembur_keperluan', 'lembur_keperluan', '`lembur_keperluan`', '`lembur_keperluan`', 200, -1, FALSE, '`lembur_keperluan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->lembur_keperluan->Sortable = TRUE; // Allow sort
		$this->fields['lembur_keperluan'] = &$this->lembur_keperluan;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`lembur`";
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
			if (array_key_exists('lembur_tgl', $rs))
				ew_AddFilter($where, ew_QuotedName('lembur_tgl', $this->DBID) . '=' . ew_QuotedValue($rs['lembur_tgl'], $this->lembur_tgl->FldDataType, $this->DBID));
			if (array_key_exists('lembur_mulai', $rs))
				ew_AddFilter($where, ew_QuotedName('lembur_mulai', $this->DBID) . '=' . ew_QuotedValue($rs['lembur_mulai'], $this->lembur_mulai->FldDataType, $this->DBID));
			if (array_key_exists('lembur_selesai', $rs))
				ew_AddFilter($where, ew_QuotedName('lembur_selesai', $this->DBID) . '=' . ew_QuotedValue($rs['lembur_selesai'], $this->lembur_selesai->FldDataType, $this->DBID));
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
		return "`pegawai_id` = @pegawai_id@ AND `lembur_tgl` = '@lembur_tgl@' AND `lembur_mulai` = '@lembur_mulai@' AND `lembur_selesai` = '@lembur_selesai@'";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->pegawai_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@pegawai_id@", ew_AdjustSql($this->pegawai_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		$sKeyFilter = str_replace("@lembur_tgl@", ew_AdjustSql(ew_UnFormatDateTime($this->lembur_tgl->CurrentValue,0), $this->DBID), $sKeyFilter); // Replace key value
		$sKeyFilter = str_replace("@lembur_mulai@", ew_AdjustSql($this->lembur_mulai->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		$sKeyFilter = str_replace("@lembur_selesai@", ew_AdjustSql($this->lembur_selesai->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "lemburlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "lemburlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("lemburview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("lemburview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "lemburadd.php?" . $this->UrlParm($parm);
		else
			$url = "lemburadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("lemburedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("lemburadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("lemburdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "pegawai_id:" . ew_VarToJson($this->pegawai_id->CurrentValue, "number", "'");
		$json .= ",lembur_tgl:" . ew_VarToJson($this->lembur_tgl->CurrentValue, "string", "'");
		$json .= ",lembur_mulai:" . ew_VarToJson($this->lembur_mulai->CurrentValue, "string", "'");
		$json .= ",lembur_selesai:" . ew_VarToJson($this->lembur_selesai->CurrentValue, "string", "'");
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
		if (!is_null($this->lembur_tgl->CurrentValue)) {
			$sUrl .= "&lembur_tgl=" . urlencode($this->lembur_tgl->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->lembur_mulai->CurrentValue)) {
			$sUrl .= "&lembur_mulai=" . urlencode($this->lembur_mulai->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->lembur_selesai->CurrentValue)) {
			$sUrl .= "&lembur_selesai=" . urlencode($this->lembur_selesai->CurrentValue);
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
			if ($isPost && isset($_POST["lembur_tgl"]))
				$arKey[] = ew_StripSlashes($_POST["lembur_tgl"]);
			elseif (isset($_GET["lembur_tgl"]))
				$arKey[] = ew_StripSlashes($_GET["lembur_tgl"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["lembur_mulai"]))
				$arKey[] = ew_StripSlashes($_POST["lembur_mulai"]);
			elseif (isset($_GET["lembur_mulai"]))
				$arKey[] = ew_StripSlashes($_GET["lembur_mulai"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["lembur_selesai"]))
				$arKey[] = ew_StripSlashes($_POST["lembur_selesai"]);
			elseif (isset($_GET["lembur_selesai"]))
				$arKey[] = ew_StripSlashes($_GET["lembur_selesai"]);
			else
				$arKeys = NULL; // Do not setup
			if (is_array($arKeys)) $arKeys[] = $arKey;

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_array($key) || count($key) <> 4)
					continue; // Just skip so other keys will still work
				if (!is_numeric($key[0])) // pegawai_id
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
			$this->lembur_tgl->CurrentValue = $key[1];
			$this->lembur_mulai->CurrentValue = $key[2];
			$this->lembur_selesai->CurrentValue = $key[3];
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
		$this->lembur_tgl->setDbValue($rs->fields('lembur_tgl'));
		$this->lembur_mulai->setDbValue($rs->fields('lembur_mulai'));
		$this->lembur_selesai->setDbValue($rs->fields('lembur_selesai'));
		$this->lembur_urut->setDbValue($rs->fields('lembur_urut'));
		$this->type_ot->setDbValue($rs->fields('type_ot'));
		$this->lembur_durasi_min->setDbValue($rs->fields('lembur_durasi_min'));
		$this->lembur_durasi_max->setDbValue($rs->fields('lembur_durasi_max'));
		$this->lembur_keperluan->setDbValue($rs->fields('lembur_keperluan'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// pegawai_id
		// lembur_tgl
		// lembur_mulai
		// lembur_selesai
		// lembur_urut
		// type_ot
		// lembur_durasi_min
		// lembur_durasi_max
		// lembur_keperluan
		// pegawai_id

		$this->pegawai_id->ViewValue = $this->pegawai_id->CurrentValue;
		$this->pegawai_id->ViewCustomAttributes = "";

		// lembur_tgl
		$this->lembur_tgl->ViewValue = $this->lembur_tgl->CurrentValue;
		$this->lembur_tgl->ViewValue = ew_FormatDateTime($this->lembur_tgl->ViewValue, 0);
		$this->lembur_tgl->ViewCustomAttributes = "";

		// lembur_mulai
		$this->lembur_mulai->ViewValue = $this->lembur_mulai->CurrentValue;
		$this->lembur_mulai->ViewCustomAttributes = "";

		// lembur_selesai
		$this->lembur_selesai->ViewValue = $this->lembur_selesai->CurrentValue;
		$this->lembur_selesai->ViewCustomAttributes = "";

		// lembur_urut
		$this->lembur_urut->ViewValue = $this->lembur_urut->CurrentValue;
		$this->lembur_urut->ViewCustomAttributes = "";

		// type_ot
		$this->type_ot->ViewValue = $this->type_ot->CurrentValue;
		$this->type_ot->ViewCustomAttributes = "";

		// lembur_durasi_min
		$this->lembur_durasi_min->ViewValue = $this->lembur_durasi_min->CurrentValue;
		$this->lembur_durasi_min->ViewCustomAttributes = "";

		// lembur_durasi_max
		$this->lembur_durasi_max->ViewValue = $this->lembur_durasi_max->CurrentValue;
		$this->lembur_durasi_max->ViewCustomAttributes = "";

		// lembur_keperluan
		$this->lembur_keperluan->ViewValue = $this->lembur_keperluan->CurrentValue;
		$this->lembur_keperluan->ViewCustomAttributes = "";

		// pegawai_id
		$this->pegawai_id->LinkCustomAttributes = "";
		$this->pegawai_id->HrefValue = "";
		$this->pegawai_id->TooltipValue = "";

		// lembur_tgl
		$this->lembur_tgl->LinkCustomAttributes = "";
		$this->lembur_tgl->HrefValue = "";
		$this->lembur_tgl->TooltipValue = "";

		// lembur_mulai
		$this->lembur_mulai->LinkCustomAttributes = "";
		$this->lembur_mulai->HrefValue = "";
		$this->lembur_mulai->TooltipValue = "";

		// lembur_selesai
		$this->lembur_selesai->LinkCustomAttributes = "";
		$this->lembur_selesai->HrefValue = "";
		$this->lembur_selesai->TooltipValue = "";

		// lembur_urut
		$this->lembur_urut->LinkCustomAttributes = "";
		$this->lembur_urut->HrefValue = "";
		$this->lembur_urut->TooltipValue = "";

		// type_ot
		$this->type_ot->LinkCustomAttributes = "";
		$this->type_ot->HrefValue = "";
		$this->type_ot->TooltipValue = "";

		// lembur_durasi_min
		$this->lembur_durasi_min->LinkCustomAttributes = "";
		$this->lembur_durasi_min->HrefValue = "";
		$this->lembur_durasi_min->TooltipValue = "";

		// lembur_durasi_max
		$this->lembur_durasi_max->LinkCustomAttributes = "";
		$this->lembur_durasi_max->HrefValue = "";
		$this->lembur_durasi_max->TooltipValue = "";

		// lembur_keperluan
		$this->lembur_keperluan->LinkCustomAttributes = "";
		$this->lembur_keperluan->HrefValue = "";
		$this->lembur_keperluan->TooltipValue = "";

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

		// lembur_tgl
		$this->lembur_tgl->EditAttrs["class"] = "form-control";
		$this->lembur_tgl->EditCustomAttributes = "";
		$this->lembur_tgl->EditValue = $this->lembur_tgl->CurrentValue;
		$this->lembur_tgl->EditValue = ew_FormatDateTime($this->lembur_tgl->EditValue, 0);
		$this->lembur_tgl->ViewCustomAttributes = "";

		// lembur_mulai
		$this->lembur_mulai->EditAttrs["class"] = "form-control";
		$this->lembur_mulai->EditCustomAttributes = "";
		$this->lembur_mulai->EditValue = $this->lembur_mulai->CurrentValue;
		$this->lembur_mulai->ViewCustomAttributes = "";

		// lembur_selesai
		$this->lembur_selesai->EditAttrs["class"] = "form-control";
		$this->lembur_selesai->EditCustomAttributes = "";
		$this->lembur_selesai->EditValue = $this->lembur_selesai->CurrentValue;
		$this->lembur_selesai->ViewCustomAttributes = "";

		// lembur_urut
		$this->lembur_urut->EditAttrs["class"] = "form-control";
		$this->lembur_urut->EditCustomAttributes = "";
		$this->lembur_urut->EditValue = $this->lembur_urut->CurrentValue;
		$this->lembur_urut->PlaceHolder = ew_RemoveHtml($this->lembur_urut->FldCaption());

		// type_ot
		$this->type_ot->EditAttrs["class"] = "form-control";
		$this->type_ot->EditCustomAttributes = "";
		$this->type_ot->EditValue = $this->type_ot->CurrentValue;
		$this->type_ot->PlaceHolder = ew_RemoveHtml($this->type_ot->FldCaption());

		// lembur_durasi_min
		$this->lembur_durasi_min->EditAttrs["class"] = "form-control";
		$this->lembur_durasi_min->EditCustomAttributes = "";
		$this->lembur_durasi_min->EditValue = $this->lembur_durasi_min->CurrentValue;
		$this->lembur_durasi_min->PlaceHolder = ew_RemoveHtml($this->lembur_durasi_min->FldCaption());

		// lembur_durasi_max
		$this->lembur_durasi_max->EditAttrs["class"] = "form-control";
		$this->lembur_durasi_max->EditCustomAttributes = "";
		$this->lembur_durasi_max->EditValue = $this->lembur_durasi_max->CurrentValue;
		$this->lembur_durasi_max->PlaceHolder = ew_RemoveHtml($this->lembur_durasi_max->FldCaption());

		// lembur_keperluan
		$this->lembur_keperluan->EditAttrs["class"] = "form-control";
		$this->lembur_keperluan->EditCustomAttributes = "";
		$this->lembur_keperluan->EditValue = $this->lembur_keperluan->CurrentValue;
		$this->lembur_keperluan->PlaceHolder = ew_RemoveHtml($this->lembur_keperluan->FldCaption());

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
					if ($this->lembur_tgl->Exportable) $Doc->ExportCaption($this->lembur_tgl);
					if ($this->lembur_mulai->Exportable) $Doc->ExportCaption($this->lembur_mulai);
					if ($this->lembur_selesai->Exportable) $Doc->ExportCaption($this->lembur_selesai);
					if ($this->lembur_urut->Exportable) $Doc->ExportCaption($this->lembur_urut);
					if ($this->type_ot->Exportable) $Doc->ExportCaption($this->type_ot);
					if ($this->lembur_durasi_min->Exportable) $Doc->ExportCaption($this->lembur_durasi_min);
					if ($this->lembur_durasi_max->Exportable) $Doc->ExportCaption($this->lembur_durasi_max);
					if ($this->lembur_keperluan->Exportable) $Doc->ExportCaption($this->lembur_keperluan);
				} else {
					if ($this->pegawai_id->Exportable) $Doc->ExportCaption($this->pegawai_id);
					if ($this->lembur_tgl->Exportable) $Doc->ExportCaption($this->lembur_tgl);
					if ($this->lembur_mulai->Exportable) $Doc->ExportCaption($this->lembur_mulai);
					if ($this->lembur_selesai->Exportable) $Doc->ExportCaption($this->lembur_selesai);
					if ($this->lembur_urut->Exportable) $Doc->ExportCaption($this->lembur_urut);
					if ($this->type_ot->Exportable) $Doc->ExportCaption($this->type_ot);
					if ($this->lembur_durasi_min->Exportable) $Doc->ExportCaption($this->lembur_durasi_min);
					if ($this->lembur_durasi_max->Exportable) $Doc->ExportCaption($this->lembur_durasi_max);
					if ($this->lembur_keperluan->Exportable) $Doc->ExportCaption($this->lembur_keperluan);
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
						if ($this->lembur_tgl->Exportable) $Doc->ExportField($this->lembur_tgl);
						if ($this->lembur_mulai->Exportable) $Doc->ExportField($this->lembur_mulai);
						if ($this->lembur_selesai->Exportable) $Doc->ExportField($this->lembur_selesai);
						if ($this->lembur_urut->Exportable) $Doc->ExportField($this->lembur_urut);
						if ($this->type_ot->Exportable) $Doc->ExportField($this->type_ot);
						if ($this->lembur_durasi_min->Exportable) $Doc->ExportField($this->lembur_durasi_min);
						if ($this->lembur_durasi_max->Exportable) $Doc->ExportField($this->lembur_durasi_max);
						if ($this->lembur_keperluan->Exportable) $Doc->ExportField($this->lembur_keperluan);
					} else {
						if ($this->pegawai_id->Exportable) $Doc->ExportField($this->pegawai_id);
						if ($this->lembur_tgl->Exportable) $Doc->ExportField($this->lembur_tgl);
						if ($this->lembur_mulai->Exportable) $Doc->ExportField($this->lembur_mulai);
						if ($this->lembur_selesai->Exportable) $Doc->ExportField($this->lembur_selesai);
						if ($this->lembur_urut->Exportable) $Doc->ExportField($this->lembur_urut);
						if ($this->type_ot->Exportable) $Doc->ExportField($this->type_ot);
						if ($this->lembur_durasi_min->Exportable) $Doc->ExportField($this->lembur_durasi_min);
						if ($this->lembur_durasi_max->Exportable) $Doc->ExportField($this->lembur_durasi_max);
						if ($this->lembur_keperluan->Exportable) $Doc->ExportField($this->lembur_keperluan);
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
