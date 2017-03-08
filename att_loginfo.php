<?php

// Global variable for table object
$att_log = NULL;

//
// Table class for att_log
//
class catt_log extends cTable {
	var $sn;
	var $scan_date;
	var $pin;
	var $verifymode;
	var $inoutmode;
	var $reserved;
	var $work_code;
	var $att_id;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'att_log';
		$this->TableName = 'att_log';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`att_log`";
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

		// sn
		$this->sn = new cField('att_log', 'att_log', 'x_sn', 'sn', '`sn`', '`sn`', 200, -1, FALSE, '`sn`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->sn->Sortable = TRUE; // Allow sort
		$this->fields['sn'] = &$this->sn;

		// scan_date
		$this->scan_date = new cField('att_log', 'att_log', 'x_scan_date', 'scan_date', '`scan_date`', ew_CastDateFieldForLike('`scan_date`', 0, "DB"), 135, 0, FALSE, '`scan_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->scan_date->Sortable = TRUE; // Allow sort
		$this->scan_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['scan_date'] = &$this->scan_date;

		// pin
		$this->pin = new cField('att_log', 'att_log', 'x_pin', 'pin', '`pin`', '`pin`', 200, -1, FALSE, '`pin`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pin->Sortable = TRUE; // Allow sort
		$this->fields['pin'] = &$this->pin;

		// verifymode
		$this->verifymode = new cField('att_log', 'att_log', 'x_verifymode', 'verifymode', '`verifymode`', '`verifymode`', 3, -1, FALSE, '`verifymode`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->verifymode->Sortable = TRUE; // Allow sort
		$this->verifymode->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['verifymode'] = &$this->verifymode;

		// inoutmode
		$this->inoutmode = new cField('att_log', 'att_log', 'x_inoutmode', 'inoutmode', '`inoutmode`', '`inoutmode`', 3, -1, FALSE, '`inoutmode`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->inoutmode->Sortable = TRUE; // Allow sort
		$this->inoutmode->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['inoutmode'] = &$this->inoutmode;

		// reserved
		$this->reserved = new cField('att_log', 'att_log', 'x_reserved', 'reserved', '`reserved`', '`reserved`', 3, -1, FALSE, '`reserved`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->reserved->Sortable = TRUE; // Allow sort
		$this->reserved->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['reserved'] = &$this->reserved;

		// work_code
		$this->work_code = new cField('att_log', 'att_log', 'x_work_code', 'work_code', '`work_code`', '`work_code`', 3, -1, FALSE, '`work_code`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->work_code->Sortable = TRUE; // Allow sort
		$this->work_code->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['work_code'] = &$this->work_code;

		// att_id
		$this->att_id = new cField('att_log', 'att_log', 'x_att_id', 'att_id', '`att_id`', '`att_id`', 200, -1, FALSE, '`att_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->att_id->Sortable = TRUE; // Allow sort
		$this->fields['att_id'] = &$this->att_id;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`att_log`";
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
			if (array_key_exists('sn', $rs))
				ew_AddFilter($where, ew_QuotedName('sn', $this->DBID) . '=' . ew_QuotedValue($rs['sn'], $this->sn->FldDataType, $this->DBID));
			if (array_key_exists('scan_date', $rs))
				ew_AddFilter($where, ew_QuotedName('scan_date', $this->DBID) . '=' . ew_QuotedValue($rs['scan_date'], $this->scan_date->FldDataType, $this->DBID));
			if (array_key_exists('pin', $rs))
				ew_AddFilter($where, ew_QuotedName('pin', $this->DBID) . '=' . ew_QuotedValue($rs['pin'], $this->pin->FldDataType, $this->DBID));
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
		return "`sn` = '@sn@' AND `scan_date` = '@scan_date@' AND `pin` = '@pin@'";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		$sKeyFilter = str_replace("@sn@", ew_AdjustSql($this->sn->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		$sKeyFilter = str_replace("@scan_date@", ew_AdjustSql(ew_UnFormatDateTime($this->scan_date->CurrentValue,0), $this->DBID), $sKeyFilter); // Replace key value
		$sKeyFilter = str_replace("@pin@", ew_AdjustSql($this->pin->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "att_loglist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "att_loglist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("att_logview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("att_logview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "att_logadd.php?" . $this->UrlParm($parm);
		else
			$url = "att_logadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("att_logedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("att_logadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("att_logdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "sn:" . ew_VarToJson($this->sn->CurrentValue, "string", "'");
		$json .= ",scan_date:" . ew_VarToJson($this->scan_date->CurrentValue, "string", "'");
		$json .= ",pin:" . ew_VarToJson($this->pin->CurrentValue, "string", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->sn->CurrentValue)) {
			$sUrl .= "sn=" . urlencode($this->sn->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->scan_date->CurrentValue)) {
			$sUrl .= "&scan_date=" . urlencode($this->scan_date->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->pin->CurrentValue)) {
			$sUrl .= "&pin=" . urlencode($this->pin->CurrentValue);
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
			if ($isPost && isset($_POST["sn"]))
				$arKey[] = ew_StripSlashes($_POST["sn"]);
			elseif (isset($_GET["sn"]))
				$arKey[] = ew_StripSlashes($_GET["sn"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["scan_date"]))
				$arKey[] = ew_StripSlashes($_POST["scan_date"]);
			elseif (isset($_GET["scan_date"]))
				$arKey[] = ew_StripSlashes($_GET["scan_date"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["pin"]))
				$arKey[] = ew_StripSlashes($_POST["pin"]);
			elseif (isset($_GET["pin"]))
				$arKey[] = ew_StripSlashes($_GET["pin"]);
			else
				$arKeys = NULL; // Do not setup
			if (is_array($arKeys)) $arKeys[] = $arKey;

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_array($key) || count($key) <> 3)
					continue; // Just skip so other keys will still work
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
			$this->sn->CurrentValue = $key[0];
			$this->scan_date->CurrentValue = $key[1];
			$this->pin->CurrentValue = $key[2];
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
		$this->sn->setDbValue($rs->fields('sn'));
		$this->scan_date->setDbValue($rs->fields('scan_date'));
		$this->pin->setDbValue($rs->fields('pin'));
		$this->verifymode->setDbValue($rs->fields('verifymode'));
		$this->inoutmode->setDbValue($rs->fields('inoutmode'));
		$this->reserved->setDbValue($rs->fields('reserved'));
		$this->work_code->setDbValue($rs->fields('work_code'));
		$this->att_id->setDbValue($rs->fields('att_id'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// sn
		// scan_date
		// pin
		// verifymode
		// inoutmode
		// reserved
		// work_code
		// att_id
		// sn

		$this->sn->ViewValue = $this->sn->CurrentValue;
		$this->sn->ViewCustomAttributes = "";

		// scan_date
		$this->scan_date->ViewValue = $this->scan_date->CurrentValue;
		$this->scan_date->ViewValue = ew_FormatDateTime($this->scan_date->ViewValue, 0);
		$this->scan_date->ViewCustomAttributes = "";

		// pin
		$this->pin->ViewValue = $this->pin->CurrentValue;
		$this->pin->ViewCustomAttributes = "";

		// verifymode
		$this->verifymode->ViewValue = $this->verifymode->CurrentValue;
		$this->verifymode->ViewCustomAttributes = "";

		// inoutmode
		$this->inoutmode->ViewValue = $this->inoutmode->CurrentValue;
		$this->inoutmode->ViewCustomAttributes = "";

		// reserved
		$this->reserved->ViewValue = $this->reserved->CurrentValue;
		$this->reserved->ViewCustomAttributes = "";

		// work_code
		$this->work_code->ViewValue = $this->work_code->CurrentValue;
		$this->work_code->ViewCustomAttributes = "";

		// att_id
		$this->att_id->ViewValue = $this->att_id->CurrentValue;
		$this->att_id->ViewCustomAttributes = "";

		// sn
		$this->sn->LinkCustomAttributes = "";
		$this->sn->HrefValue = "";
		$this->sn->TooltipValue = "";

		// scan_date
		$this->scan_date->LinkCustomAttributes = "";
		$this->scan_date->HrefValue = "";
		$this->scan_date->TooltipValue = "";

		// pin
		$this->pin->LinkCustomAttributes = "";
		$this->pin->HrefValue = "";
		$this->pin->TooltipValue = "";

		// verifymode
		$this->verifymode->LinkCustomAttributes = "";
		$this->verifymode->HrefValue = "";
		$this->verifymode->TooltipValue = "";

		// inoutmode
		$this->inoutmode->LinkCustomAttributes = "";
		$this->inoutmode->HrefValue = "";
		$this->inoutmode->TooltipValue = "";

		// reserved
		$this->reserved->LinkCustomAttributes = "";
		$this->reserved->HrefValue = "";
		$this->reserved->TooltipValue = "";

		// work_code
		$this->work_code->LinkCustomAttributes = "";
		$this->work_code->HrefValue = "";
		$this->work_code->TooltipValue = "";

		// att_id
		$this->att_id->LinkCustomAttributes = "";
		$this->att_id->HrefValue = "";
		$this->att_id->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// sn
		$this->sn->EditAttrs["class"] = "form-control";
		$this->sn->EditCustomAttributes = "";
		$this->sn->EditValue = $this->sn->CurrentValue;
		$this->sn->ViewCustomAttributes = "";

		// scan_date
		$this->scan_date->EditAttrs["class"] = "form-control";
		$this->scan_date->EditCustomAttributes = "";
		$this->scan_date->EditValue = $this->scan_date->CurrentValue;
		$this->scan_date->EditValue = ew_FormatDateTime($this->scan_date->EditValue, 0);
		$this->scan_date->ViewCustomAttributes = "";

		// pin
		$this->pin->EditAttrs["class"] = "form-control";
		$this->pin->EditCustomAttributes = "";
		$this->pin->EditValue = $this->pin->CurrentValue;
		$this->pin->ViewCustomAttributes = "";

		// verifymode
		$this->verifymode->EditAttrs["class"] = "form-control";
		$this->verifymode->EditCustomAttributes = "";
		$this->verifymode->EditValue = $this->verifymode->CurrentValue;
		$this->verifymode->PlaceHolder = ew_RemoveHtml($this->verifymode->FldCaption());

		// inoutmode
		$this->inoutmode->EditAttrs["class"] = "form-control";
		$this->inoutmode->EditCustomAttributes = "";
		$this->inoutmode->EditValue = $this->inoutmode->CurrentValue;
		$this->inoutmode->PlaceHolder = ew_RemoveHtml($this->inoutmode->FldCaption());

		// reserved
		$this->reserved->EditAttrs["class"] = "form-control";
		$this->reserved->EditCustomAttributes = "";
		$this->reserved->EditValue = $this->reserved->CurrentValue;
		$this->reserved->PlaceHolder = ew_RemoveHtml($this->reserved->FldCaption());

		// work_code
		$this->work_code->EditAttrs["class"] = "form-control";
		$this->work_code->EditCustomAttributes = "";
		$this->work_code->EditValue = $this->work_code->CurrentValue;
		$this->work_code->PlaceHolder = ew_RemoveHtml($this->work_code->FldCaption());

		// att_id
		$this->att_id->EditAttrs["class"] = "form-control";
		$this->att_id->EditCustomAttributes = "";
		$this->att_id->EditValue = $this->att_id->CurrentValue;
		$this->att_id->PlaceHolder = ew_RemoveHtml($this->att_id->FldCaption());

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
					if ($this->sn->Exportable) $Doc->ExportCaption($this->sn);
					if ($this->scan_date->Exportable) $Doc->ExportCaption($this->scan_date);
					if ($this->pin->Exportable) $Doc->ExportCaption($this->pin);
					if ($this->verifymode->Exportable) $Doc->ExportCaption($this->verifymode);
					if ($this->inoutmode->Exportable) $Doc->ExportCaption($this->inoutmode);
					if ($this->reserved->Exportable) $Doc->ExportCaption($this->reserved);
					if ($this->work_code->Exportable) $Doc->ExportCaption($this->work_code);
					if ($this->att_id->Exportable) $Doc->ExportCaption($this->att_id);
				} else {
					if ($this->sn->Exportable) $Doc->ExportCaption($this->sn);
					if ($this->scan_date->Exportable) $Doc->ExportCaption($this->scan_date);
					if ($this->pin->Exportable) $Doc->ExportCaption($this->pin);
					if ($this->verifymode->Exportable) $Doc->ExportCaption($this->verifymode);
					if ($this->inoutmode->Exportable) $Doc->ExportCaption($this->inoutmode);
					if ($this->reserved->Exportable) $Doc->ExportCaption($this->reserved);
					if ($this->work_code->Exportable) $Doc->ExportCaption($this->work_code);
					if ($this->att_id->Exportable) $Doc->ExportCaption($this->att_id);
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
						if ($this->sn->Exportable) $Doc->ExportField($this->sn);
						if ($this->scan_date->Exportable) $Doc->ExportField($this->scan_date);
						if ($this->pin->Exportable) $Doc->ExportField($this->pin);
						if ($this->verifymode->Exportable) $Doc->ExportField($this->verifymode);
						if ($this->inoutmode->Exportable) $Doc->ExportField($this->inoutmode);
						if ($this->reserved->Exportable) $Doc->ExportField($this->reserved);
						if ($this->work_code->Exportable) $Doc->ExportField($this->work_code);
						if ($this->att_id->Exportable) $Doc->ExportField($this->att_id);
					} else {
						if ($this->sn->Exportable) $Doc->ExportField($this->sn);
						if ($this->scan_date->Exportable) $Doc->ExportField($this->scan_date);
						if ($this->pin->Exportable) $Doc->ExportField($this->pin);
						if ($this->verifymode->Exportable) $Doc->ExportField($this->verifymode);
						if ($this->inoutmode->Exportable) $Doc->ExportField($this->inoutmode);
						if ($this->reserved->Exportable) $Doc->ExportField($this->reserved);
						if ($this->work_code->Exportable) $Doc->ExportField($this->work_code);
						if ($this->att_id->Exportable) $Doc->ExportField($this->att_id);
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
