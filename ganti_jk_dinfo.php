<?php

// Global variable for table object
$ganti_jk_d = NULL;

//
// Table class for ganti_jk_d
//
class cganti_jk_d extends cTable {
	var $ganti_jk_id;
	var $tgl_ganti_jk;
	var $jns_ganti_jk;
	var $jk_id;
	var $pegawai_id;
	var $libur;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'ganti_jk_d';
		$this->TableName = 'ganti_jk_d';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`ganti_jk_d`";
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

		// ganti_jk_id
		$this->ganti_jk_id = new cField('ganti_jk_d', 'ganti_jk_d', 'x_ganti_jk_id', 'ganti_jk_id', '`ganti_jk_id`', '`ganti_jk_id`', 3, -1, FALSE, '`ganti_jk_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->ganti_jk_id->Sortable = TRUE; // Allow sort
		$this->ganti_jk_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ganti_jk_id'] = &$this->ganti_jk_id;

		// tgl_ganti_jk
		$this->tgl_ganti_jk = new cField('ganti_jk_d', 'ganti_jk_d', 'x_tgl_ganti_jk', 'tgl_ganti_jk', '`tgl_ganti_jk`', ew_CastDateFieldForLike('`tgl_ganti_jk`', 0, "DB"), 133, 0, FALSE, '`tgl_ganti_jk`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tgl_ganti_jk->Sortable = TRUE; // Allow sort
		$this->tgl_ganti_jk->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['tgl_ganti_jk'] = &$this->tgl_ganti_jk;

		// jns_ganti_jk
		$this->jns_ganti_jk = new cField('ganti_jk_d', 'ganti_jk_d', 'x_jns_ganti_jk', 'jns_ganti_jk', '`jns_ganti_jk`', '`jns_ganti_jk`', 16, -1, FALSE, '`jns_ganti_jk`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jns_ganti_jk->Sortable = TRUE; // Allow sort
		$this->jns_ganti_jk->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['jns_ganti_jk'] = &$this->jns_ganti_jk;

		// jk_id
		$this->jk_id = new cField('ganti_jk_d', 'ganti_jk_d', 'x_jk_id', 'jk_id', '`jk_id`', '`jk_id`', 3, -1, FALSE, '`jk_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jk_id->Sortable = TRUE; // Allow sort
		$this->jk_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['jk_id'] = &$this->jk_id;

		// pegawai_id
		$this->pegawai_id = new cField('ganti_jk_d', 'ganti_jk_d', 'x_pegawai_id', 'pegawai_id', '`pegawai_id`', '`pegawai_id`', 3, -1, FALSE, '`pegawai_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pegawai_id->Sortable = TRUE; // Allow sort
		$this->pegawai_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['pegawai_id'] = &$this->pegawai_id;

		// libur
		$this->libur = new cField('ganti_jk_d', 'ganti_jk_d', 'x_libur', 'libur', '`libur`', '`libur`', 16, -1, FALSE, '`libur`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->libur->Sortable = TRUE; // Allow sort
		$this->libur->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['libur'] = &$this->libur;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`ganti_jk_d`";
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
			if (array_key_exists('ganti_jk_id', $rs))
				ew_AddFilter($where, ew_QuotedName('ganti_jk_id', $this->DBID) . '=' . ew_QuotedValue($rs['ganti_jk_id'], $this->ganti_jk_id->FldDataType, $this->DBID));
			if (array_key_exists('tgl_ganti_jk', $rs))
				ew_AddFilter($where, ew_QuotedName('tgl_ganti_jk', $this->DBID) . '=' . ew_QuotedValue($rs['tgl_ganti_jk'], $this->tgl_ganti_jk->FldDataType, $this->DBID));
			if (array_key_exists('jns_ganti_jk', $rs))
				ew_AddFilter($where, ew_QuotedName('jns_ganti_jk', $this->DBID) . '=' . ew_QuotedValue($rs['jns_ganti_jk'], $this->jns_ganti_jk->FldDataType, $this->DBID));
			if (array_key_exists('jk_id', $rs))
				ew_AddFilter($where, ew_QuotedName('jk_id', $this->DBID) . '=' . ew_QuotedValue($rs['jk_id'], $this->jk_id->FldDataType, $this->DBID));
			if (array_key_exists('pegawai_id', $rs))
				ew_AddFilter($where, ew_QuotedName('pegawai_id', $this->DBID) . '=' . ew_QuotedValue($rs['pegawai_id'], $this->pegawai_id->FldDataType, $this->DBID));
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
		return "`ganti_jk_id` = @ganti_jk_id@ AND `tgl_ganti_jk` = '@tgl_ganti_jk@' AND `jns_ganti_jk` = @jns_ganti_jk@ AND `jk_id` = @jk_id@ AND `pegawai_id` = @pegawai_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->ganti_jk_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@ganti_jk_id@", ew_AdjustSql($this->ganti_jk_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		$sKeyFilter = str_replace("@tgl_ganti_jk@", ew_AdjustSql(ew_UnFormatDateTime($this->tgl_ganti_jk->CurrentValue,0), $this->DBID), $sKeyFilter); // Replace key value
		if (!is_numeric($this->jns_ganti_jk->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@jns_ganti_jk@", ew_AdjustSql($this->jns_ganti_jk->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		if (!is_numeric($this->jk_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@jk_id@", ew_AdjustSql($this->jk_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		if (!is_numeric($this->pegawai_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@pegawai_id@", ew_AdjustSql($this->pegawai_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "ganti_jk_dlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "ganti_jk_dlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("ganti_jk_dview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("ganti_jk_dview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "ganti_jk_dadd.php?" . $this->UrlParm($parm);
		else
			$url = "ganti_jk_dadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("ganti_jk_dedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("ganti_jk_dadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("ganti_jk_ddelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "ganti_jk_id:" . ew_VarToJson($this->ganti_jk_id->CurrentValue, "number", "'");
		$json .= ",tgl_ganti_jk:" . ew_VarToJson($this->tgl_ganti_jk->CurrentValue, "string", "'");
		$json .= ",jns_ganti_jk:" . ew_VarToJson($this->jns_ganti_jk->CurrentValue, "number", "'");
		$json .= ",jk_id:" . ew_VarToJson($this->jk_id->CurrentValue, "number", "'");
		$json .= ",pegawai_id:" . ew_VarToJson($this->pegawai_id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->ganti_jk_id->CurrentValue)) {
			$sUrl .= "ganti_jk_id=" . urlencode($this->ganti_jk_id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->tgl_ganti_jk->CurrentValue)) {
			$sUrl .= "&tgl_ganti_jk=" . urlencode($this->tgl_ganti_jk->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->jns_ganti_jk->CurrentValue)) {
			$sUrl .= "&jns_ganti_jk=" . urlencode($this->jns_ganti_jk->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->jk_id->CurrentValue)) {
			$sUrl .= "&jk_id=" . urlencode($this->jk_id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->pegawai_id->CurrentValue)) {
			$sUrl .= "&pegawai_id=" . urlencode($this->pegawai_id->CurrentValue);
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
			if ($isPost && isset($_POST["ganti_jk_id"]))
				$arKey[] = ew_StripSlashes($_POST["ganti_jk_id"]);
			elseif (isset($_GET["ganti_jk_id"]))
				$arKey[] = ew_StripSlashes($_GET["ganti_jk_id"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["tgl_ganti_jk"]))
				$arKey[] = ew_StripSlashes($_POST["tgl_ganti_jk"]);
			elseif (isset($_GET["tgl_ganti_jk"]))
				$arKey[] = ew_StripSlashes($_GET["tgl_ganti_jk"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["jns_ganti_jk"]))
				$arKey[] = ew_StripSlashes($_POST["jns_ganti_jk"]);
			elseif (isset($_GET["jns_ganti_jk"]))
				$arKey[] = ew_StripSlashes($_GET["jns_ganti_jk"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["jk_id"]))
				$arKey[] = ew_StripSlashes($_POST["jk_id"]);
			elseif (isset($_GET["jk_id"]))
				$arKey[] = ew_StripSlashes($_GET["jk_id"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["pegawai_id"]))
				$arKey[] = ew_StripSlashes($_POST["pegawai_id"]);
			elseif (isset($_GET["pegawai_id"]))
				$arKey[] = ew_StripSlashes($_GET["pegawai_id"]);
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
				if (!is_numeric($key[0])) // ganti_jk_id
					continue;
				if (!is_numeric($key[2])) // jns_ganti_jk
					continue;
				if (!is_numeric($key[3])) // jk_id
					continue;
				if (!is_numeric($key[4])) // pegawai_id
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
			$this->ganti_jk_id->CurrentValue = $key[0];
			$this->tgl_ganti_jk->CurrentValue = $key[1];
			$this->jns_ganti_jk->CurrentValue = $key[2];
			$this->jk_id->CurrentValue = $key[3];
			$this->pegawai_id->CurrentValue = $key[4];
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
		$this->ganti_jk_id->setDbValue($rs->fields('ganti_jk_id'));
		$this->tgl_ganti_jk->setDbValue($rs->fields('tgl_ganti_jk'));
		$this->jns_ganti_jk->setDbValue($rs->fields('jns_ganti_jk'));
		$this->jk_id->setDbValue($rs->fields('jk_id'));
		$this->pegawai_id->setDbValue($rs->fields('pegawai_id'));
		$this->libur->setDbValue($rs->fields('libur'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// ganti_jk_id
		// tgl_ganti_jk
		// jns_ganti_jk
		// jk_id
		// pegawai_id
		// libur
		// ganti_jk_id

		$this->ganti_jk_id->ViewValue = $this->ganti_jk_id->CurrentValue;
		$this->ganti_jk_id->ViewCustomAttributes = "";

		// tgl_ganti_jk
		$this->tgl_ganti_jk->ViewValue = $this->tgl_ganti_jk->CurrentValue;
		$this->tgl_ganti_jk->ViewValue = ew_FormatDateTime($this->tgl_ganti_jk->ViewValue, 0);
		$this->tgl_ganti_jk->ViewCustomAttributes = "";

		// jns_ganti_jk
		$this->jns_ganti_jk->ViewValue = $this->jns_ganti_jk->CurrentValue;
		$this->jns_ganti_jk->ViewCustomAttributes = "";

		// jk_id
		$this->jk_id->ViewValue = $this->jk_id->CurrentValue;
		$this->jk_id->ViewCustomAttributes = "";

		// pegawai_id
		$this->pegawai_id->ViewValue = $this->pegawai_id->CurrentValue;
		$this->pegawai_id->ViewCustomAttributes = "";

		// libur
		$this->libur->ViewValue = $this->libur->CurrentValue;
		$this->libur->ViewCustomAttributes = "";

		// ganti_jk_id
		$this->ganti_jk_id->LinkCustomAttributes = "";
		$this->ganti_jk_id->HrefValue = "";
		$this->ganti_jk_id->TooltipValue = "";

		// tgl_ganti_jk
		$this->tgl_ganti_jk->LinkCustomAttributes = "";
		$this->tgl_ganti_jk->HrefValue = "";
		$this->tgl_ganti_jk->TooltipValue = "";

		// jns_ganti_jk
		$this->jns_ganti_jk->LinkCustomAttributes = "";
		$this->jns_ganti_jk->HrefValue = "";
		$this->jns_ganti_jk->TooltipValue = "";

		// jk_id
		$this->jk_id->LinkCustomAttributes = "";
		$this->jk_id->HrefValue = "";
		$this->jk_id->TooltipValue = "";

		// pegawai_id
		$this->pegawai_id->LinkCustomAttributes = "";
		$this->pegawai_id->HrefValue = "";
		$this->pegawai_id->TooltipValue = "";

		// libur
		$this->libur->LinkCustomAttributes = "";
		$this->libur->HrefValue = "";
		$this->libur->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// ganti_jk_id
		$this->ganti_jk_id->EditAttrs["class"] = "form-control";
		$this->ganti_jk_id->EditCustomAttributes = "";
		$this->ganti_jk_id->EditValue = $this->ganti_jk_id->CurrentValue;
		$this->ganti_jk_id->ViewCustomAttributes = "";

		// tgl_ganti_jk
		$this->tgl_ganti_jk->EditAttrs["class"] = "form-control";
		$this->tgl_ganti_jk->EditCustomAttributes = "";
		$this->tgl_ganti_jk->EditValue = $this->tgl_ganti_jk->CurrentValue;
		$this->tgl_ganti_jk->EditValue = ew_FormatDateTime($this->tgl_ganti_jk->EditValue, 0);
		$this->tgl_ganti_jk->ViewCustomAttributes = "";

		// jns_ganti_jk
		$this->jns_ganti_jk->EditAttrs["class"] = "form-control";
		$this->jns_ganti_jk->EditCustomAttributes = "";
		$this->jns_ganti_jk->EditValue = $this->jns_ganti_jk->CurrentValue;
		$this->jns_ganti_jk->ViewCustomAttributes = "";

		// jk_id
		$this->jk_id->EditAttrs["class"] = "form-control";
		$this->jk_id->EditCustomAttributes = "";
		$this->jk_id->EditValue = $this->jk_id->CurrentValue;
		$this->jk_id->ViewCustomAttributes = "";

		// pegawai_id
		$this->pegawai_id->EditAttrs["class"] = "form-control";
		$this->pegawai_id->EditCustomAttributes = "";
		$this->pegawai_id->EditValue = $this->pegawai_id->CurrentValue;
		$this->pegawai_id->ViewCustomAttributes = "";

		// libur
		$this->libur->EditAttrs["class"] = "form-control";
		$this->libur->EditCustomAttributes = "";
		$this->libur->EditValue = $this->libur->CurrentValue;
		$this->libur->PlaceHolder = ew_RemoveHtml($this->libur->FldCaption());

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
					if ($this->ganti_jk_id->Exportable) $Doc->ExportCaption($this->ganti_jk_id);
					if ($this->tgl_ganti_jk->Exportable) $Doc->ExportCaption($this->tgl_ganti_jk);
					if ($this->jns_ganti_jk->Exportable) $Doc->ExportCaption($this->jns_ganti_jk);
					if ($this->jk_id->Exportable) $Doc->ExportCaption($this->jk_id);
					if ($this->pegawai_id->Exportable) $Doc->ExportCaption($this->pegawai_id);
					if ($this->libur->Exportable) $Doc->ExportCaption($this->libur);
				} else {
					if ($this->ganti_jk_id->Exportable) $Doc->ExportCaption($this->ganti_jk_id);
					if ($this->tgl_ganti_jk->Exportable) $Doc->ExportCaption($this->tgl_ganti_jk);
					if ($this->jns_ganti_jk->Exportable) $Doc->ExportCaption($this->jns_ganti_jk);
					if ($this->jk_id->Exportable) $Doc->ExportCaption($this->jk_id);
					if ($this->pegawai_id->Exportable) $Doc->ExportCaption($this->pegawai_id);
					if ($this->libur->Exportable) $Doc->ExportCaption($this->libur);
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
						if ($this->ganti_jk_id->Exportable) $Doc->ExportField($this->ganti_jk_id);
						if ($this->tgl_ganti_jk->Exportable) $Doc->ExportField($this->tgl_ganti_jk);
						if ($this->jns_ganti_jk->Exportable) $Doc->ExportField($this->jns_ganti_jk);
						if ($this->jk_id->Exportable) $Doc->ExportField($this->jk_id);
						if ($this->pegawai_id->Exportable) $Doc->ExportField($this->pegawai_id);
						if ($this->libur->Exportable) $Doc->ExportField($this->libur);
					} else {
						if ($this->ganti_jk_id->Exportable) $Doc->ExportField($this->ganti_jk_id);
						if ($this->tgl_ganti_jk->Exportable) $Doc->ExportField($this->tgl_ganti_jk);
						if ($this->jns_ganti_jk->Exportable) $Doc->ExportField($this->jns_ganti_jk);
						if ($this->jk_id->Exportable) $Doc->ExportField($this->jk_id);
						if ($this->pegawai_id->Exportable) $Doc->ExportField($this->pegawai_id);
						if ($this->libur->Exportable) $Doc->ExportField($this->libur);
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
