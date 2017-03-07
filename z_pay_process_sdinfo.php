<?php

// Global variable for table object
$z_pay_process_sd = NULL;

//
// Table class for z_pay_process_sd
//
class cz_pay_process_sd extends cTable {
	var $process_id;
	var $no_urut;
	var $no_urut_ref;
	var $emp_id_auto;
	var $com_id;
	var $kondisi;
	var $rumus;
	var $subtot_payroll;
	var $jml_faktor;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'z_pay_process_sd';
		$this->TableName = 'z_pay_process_sd';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`z_pay_process_sd`";
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

		// process_id
		$this->process_id = new cField('z_pay_process_sd', 'z_pay_process_sd', 'x_process_id', 'process_id', '`process_id`', '`process_id`', 3, -1, FALSE, '`process_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->process_id->Sortable = TRUE; // Allow sort
		$this->process_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['process_id'] = &$this->process_id;

		// no_urut
		$this->no_urut = new cField('z_pay_process_sd', 'z_pay_process_sd', 'x_no_urut', 'no_urut', '`no_urut`', '`no_urut`', 2, -1, FALSE, '`no_urut`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->no_urut->Sortable = TRUE; // Allow sort
		$this->no_urut->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['no_urut'] = &$this->no_urut;

		// no_urut_ref
		$this->no_urut_ref = new cField('z_pay_process_sd', 'z_pay_process_sd', 'x_no_urut_ref', 'no_urut_ref', '`no_urut_ref`', '`no_urut_ref`', 2, -1, FALSE, '`no_urut_ref`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->no_urut_ref->Sortable = TRUE; // Allow sort
		$this->no_urut_ref->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['no_urut_ref'] = &$this->no_urut_ref;

		// emp_id_auto
		$this->emp_id_auto = new cField('z_pay_process_sd', 'z_pay_process_sd', 'x_emp_id_auto', 'emp_id_auto', '`emp_id_auto`', '`emp_id_auto`', 3, -1, FALSE, '`emp_id_auto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->emp_id_auto->Sortable = TRUE; // Allow sort
		$this->emp_id_auto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['emp_id_auto'] = &$this->emp_id_auto;

		// com_id
		$this->com_id = new cField('z_pay_process_sd', 'z_pay_process_sd', 'x_com_id', 'com_id', '`com_id`', '`com_id`', 2, -1, FALSE, '`com_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->com_id->Sortable = TRUE; // Allow sort
		$this->com_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['com_id'] = &$this->com_id;

		// kondisi
		$this->kondisi = new cField('z_pay_process_sd', 'z_pay_process_sd', 'x_kondisi', 'kondisi', '`kondisi`', '`kondisi`', 200, -1, FALSE, '`kondisi`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->kondisi->Sortable = TRUE; // Allow sort
		$this->fields['kondisi'] = &$this->kondisi;

		// rumus
		$this->rumus = new cField('z_pay_process_sd', 'z_pay_process_sd', 'x_rumus', 'rumus', '`rumus`', '`rumus`', 200, -1, FALSE, '`rumus`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->rumus->Sortable = TRUE; // Allow sort
		$this->fields['rumus'] = &$this->rumus;

		// subtot_payroll
		$this->subtot_payroll = new cField('z_pay_process_sd', 'z_pay_process_sd', 'x_subtot_payroll', 'subtot_payroll', '`subtot_payroll`', '`subtot_payroll`', 4, -1, FALSE, '`subtot_payroll`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->subtot_payroll->Sortable = TRUE; // Allow sort
		$this->subtot_payroll->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['subtot_payroll'] = &$this->subtot_payroll;

		// jml_faktor
		$this->jml_faktor = new cField('z_pay_process_sd', 'z_pay_process_sd', 'x_jml_faktor', 'jml_faktor', '`jml_faktor`', '`jml_faktor`', 4, -1, FALSE, '`jml_faktor`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jml_faktor->Sortable = TRUE; // Allow sort
		$this->jml_faktor->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['jml_faktor'] = &$this->jml_faktor;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`z_pay_process_sd`";
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
		return $conn->Execute($this->InsertSQL($rs));
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
		return $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('process_id', $rs))
				ew_AddFilter($where, ew_QuotedName('process_id', $this->DBID) . '=' . ew_QuotedValue($rs['process_id'], $this->process_id->FldDataType, $this->DBID));
			if (array_key_exists('no_urut', $rs))
				ew_AddFilter($where, ew_QuotedName('no_urut', $this->DBID) . '=' . ew_QuotedValue($rs['no_urut'], $this->no_urut->FldDataType, $this->DBID));
			if (array_key_exists('no_urut_ref', $rs))
				ew_AddFilter($where, ew_QuotedName('no_urut_ref', $this->DBID) . '=' . ew_QuotedValue($rs['no_urut_ref'], $this->no_urut_ref->FldDataType, $this->DBID));
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
		return $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`process_id` = @process_id@ AND `no_urut` = @no_urut@ AND `no_urut_ref` = @no_urut_ref@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->process_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@process_id@", ew_AdjustSql($this->process_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		if (!is_numeric($this->no_urut->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@no_urut@", ew_AdjustSql($this->no_urut->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		if (!is_numeric($this->no_urut_ref->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@no_urut_ref@", ew_AdjustSql($this->no_urut_ref->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "z_pay_process_sdlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "z_pay_process_sdlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("z_pay_process_sdview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("z_pay_process_sdview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "z_pay_process_sdadd.php?" . $this->UrlParm($parm);
		else
			$url = "z_pay_process_sdadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("z_pay_process_sdedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("z_pay_process_sdadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("z_pay_process_sddelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "process_id:" . ew_VarToJson($this->process_id->CurrentValue, "number", "'");
		$json .= ",no_urut:" . ew_VarToJson($this->no_urut->CurrentValue, "number", "'");
		$json .= ",no_urut_ref:" . ew_VarToJson($this->no_urut_ref->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->process_id->CurrentValue)) {
			$sUrl .= "process_id=" . urlencode($this->process_id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->no_urut->CurrentValue)) {
			$sUrl .= "&no_urut=" . urlencode($this->no_urut->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->no_urut_ref->CurrentValue)) {
			$sUrl .= "&no_urut_ref=" . urlencode($this->no_urut_ref->CurrentValue);
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
			if ($isPost && isset($_POST["process_id"]))
				$arKey[] = ew_StripSlashes($_POST["process_id"]);
			elseif (isset($_GET["process_id"]))
				$arKey[] = ew_StripSlashes($_GET["process_id"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["no_urut"]))
				$arKey[] = ew_StripSlashes($_POST["no_urut"]);
			elseif (isset($_GET["no_urut"]))
				$arKey[] = ew_StripSlashes($_GET["no_urut"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["no_urut_ref"]))
				$arKey[] = ew_StripSlashes($_POST["no_urut_ref"]);
			elseif (isset($_GET["no_urut_ref"]))
				$arKey[] = ew_StripSlashes($_GET["no_urut_ref"]);
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
				if (!is_numeric($key[0])) // process_id
					continue;
				if (!is_numeric($key[1])) // no_urut
					continue;
				if (!is_numeric($key[2])) // no_urut_ref
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
			$this->process_id->CurrentValue = $key[0];
			$this->no_urut->CurrentValue = $key[1];
			$this->no_urut_ref->CurrentValue = $key[2];
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
		$this->process_id->setDbValue($rs->fields('process_id'));
		$this->no_urut->setDbValue($rs->fields('no_urut'));
		$this->no_urut_ref->setDbValue($rs->fields('no_urut_ref'));
		$this->emp_id_auto->setDbValue($rs->fields('emp_id_auto'));
		$this->com_id->setDbValue($rs->fields('com_id'));
		$this->kondisi->setDbValue($rs->fields('kondisi'));
		$this->rumus->setDbValue($rs->fields('rumus'));
		$this->subtot_payroll->setDbValue($rs->fields('subtot_payroll'));
		$this->jml_faktor->setDbValue($rs->fields('jml_faktor'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// process_id
		// no_urut
		// no_urut_ref
		// emp_id_auto
		// com_id
		// kondisi
		// rumus
		// subtot_payroll
		// jml_faktor
		// process_id

		$this->process_id->ViewValue = $this->process_id->CurrentValue;
		$this->process_id->ViewCustomAttributes = "";

		// no_urut
		$this->no_urut->ViewValue = $this->no_urut->CurrentValue;
		$this->no_urut->ViewCustomAttributes = "";

		// no_urut_ref
		$this->no_urut_ref->ViewValue = $this->no_urut_ref->CurrentValue;
		$this->no_urut_ref->ViewCustomAttributes = "";

		// emp_id_auto
		$this->emp_id_auto->ViewValue = $this->emp_id_auto->CurrentValue;
		$this->emp_id_auto->ViewCustomAttributes = "";

		// com_id
		$this->com_id->ViewValue = $this->com_id->CurrentValue;
		$this->com_id->ViewCustomAttributes = "";

		// kondisi
		$this->kondisi->ViewValue = $this->kondisi->CurrentValue;
		$this->kondisi->ViewCustomAttributes = "";

		// rumus
		$this->rumus->ViewValue = $this->rumus->CurrentValue;
		$this->rumus->ViewCustomAttributes = "";

		// subtot_payroll
		$this->subtot_payroll->ViewValue = $this->subtot_payroll->CurrentValue;
		$this->subtot_payroll->ViewCustomAttributes = "";

		// jml_faktor
		$this->jml_faktor->ViewValue = $this->jml_faktor->CurrentValue;
		$this->jml_faktor->ViewCustomAttributes = "";

		// process_id
		$this->process_id->LinkCustomAttributes = "";
		$this->process_id->HrefValue = "";
		$this->process_id->TooltipValue = "";

		// no_urut
		$this->no_urut->LinkCustomAttributes = "";
		$this->no_urut->HrefValue = "";
		$this->no_urut->TooltipValue = "";

		// no_urut_ref
		$this->no_urut_ref->LinkCustomAttributes = "";
		$this->no_urut_ref->HrefValue = "";
		$this->no_urut_ref->TooltipValue = "";

		// emp_id_auto
		$this->emp_id_auto->LinkCustomAttributes = "";
		$this->emp_id_auto->HrefValue = "";
		$this->emp_id_auto->TooltipValue = "";

		// com_id
		$this->com_id->LinkCustomAttributes = "";
		$this->com_id->HrefValue = "";
		$this->com_id->TooltipValue = "";

		// kondisi
		$this->kondisi->LinkCustomAttributes = "";
		$this->kondisi->HrefValue = "";
		$this->kondisi->TooltipValue = "";

		// rumus
		$this->rumus->LinkCustomAttributes = "";
		$this->rumus->HrefValue = "";
		$this->rumus->TooltipValue = "";

		// subtot_payroll
		$this->subtot_payroll->LinkCustomAttributes = "";
		$this->subtot_payroll->HrefValue = "";
		$this->subtot_payroll->TooltipValue = "";

		// jml_faktor
		$this->jml_faktor->LinkCustomAttributes = "";
		$this->jml_faktor->HrefValue = "";
		$this->jml_faktor->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// process_id
		$this->process_id->EditAttrs["class"] = "form-control";
		$this->process_id->EditCustomAttributes = "";
		$this->process_id->EditValue = $this->process_id->CurrentValue;
		$this->process_id->ViewCustomAttributes = "";

		// no_urut
		$this->no_urut->EditAttrs["class"] = "form-control";
		$this->no_urut->EditCustomAttributes = "";
		$this->no_urut->EditValue = $this->no_urut->CurrentValue;
		$this->no_urut->ViewCustomAttributes = "";

		// no_urut_ref
		$this->no_urut_ref->EditAttrs["class"] = "form-control";
		$this->no_urut_ref->EditCustomAttributes = "";
		$this->no_urut_ref->EditValue = $this->no_urut_ref->CurrentValue;
		$this->no_urut_ref->ViewCustomAttributes = "";

		// emp_id_auto
		$this->emp_id_auto->EditAttrs["class"] = "form-control";
		$this->emp_id_auto->EditCustomAttributes = "";
		$this->emp_id_auto->EditValue = $this->emp_id_auto->CurrentValue;
		$this->emp_id_auto->PlaceHolder = ew_RemoveHtml($this->emp_id_auto->FldCaption());

		// com_id
		$this->com_id->EditAttrs["class"] = "form-control";
		$this->com_id->EditCustomAttributes = "";
		$this->com_id->EditValue = $this->com_id->CurrentValue;
		$this->com_id->PlaceHolder = ew_RemoveHtml($this->com_id->FldCaption());

		// kondisi
		$this->kondisi->EditAttrs["class"] = "form-control";
		$this->kondisi->EditCustomAttributes = "";
		$this->kondisi->EditValue = $this->kondisi->CurrentValue;
		$this->kondisi->PlaceHolder = ew_RemoveHtml($this->kondisi->FldCaption());

		// rumus
		$this->rumus->EditAttrs["class"] = "form-control";
		$this->rumus->EditCustomAttributes = "";
		$this->rumus->EditValue = $this->rumus->CurrentValue;
		$this->rumus->PlaceHolder = ew_RemoveHtml($this->rumus->FldCaption());

		// subtot_payroll
		$this->subtot_payroll->EditAttrs["class"] = "form-control";
		$this->subtot_payroll->EditCustomAttributes = "";
		$this->subtot_payroll->EditValue = $this->subtot_payroll->CurrentValue;
		$this->subtot_payroll->PlaceHolder = ew_RemoveHtml($this->subtot_payroll->FldCaption());
		if (strval($this->subtot_payroll->EditValue) <> "" && is_numeric($this->subtot_payroll->EditValue)) $this->subtot_payroll->EditValue = ew_FormatNumber($this->subtot_payroll->EditValue, -2, -1, -2, 0);

		// jml_faktor
		$this->jml_faktor->EditAttrs["class"] = "form-control";
		$this->jml_faktor->EditCustomAttributes = "";
		$this->jml_faktor->EditValue = $this->jml_faktor->CurrentValue;
		$this->jml_faktor->PlaceHolder = ew_RemoveHtml($this->jml_faktor->FldCaption());
		if (strval($this->jml_faktor->EditValue) <> "" && is_numeric($this->jml_faktor->EditValue)) $this->jml_faktor->EditValue = ew_FormatNumber($this->jml_faktor->EditValue, -2, -1, -2, 0);

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
					if ($this->process_id->Exportable) $Doc->ExportCaption($this->process_id);
					if ($this->no_urut->Exportable) $Doc->ExportCaption($this->no_urut);
					if ($this->no_urut_ref->Exportable) $Doc->ExportCaption($this->no_urut_ref);
					if ($this->emp_id_auto->Exportable) $Doc->ExportCaption($this->emp_id_auto);
					if ($this->com_id->Exportable) $Doc->ExportCaption($this->com_id);
					if ($this->kondisi->Exportable) $Doc->ExportCaption($this->kondisi);
					if ($this->rumus->Exportable) $Doc->ExportCaption($this->rumus);
					if ($this->subtot_payroll->Exportable) $Doc->ExportCaption($this->subtot_payroll);
					if ($this->jml_faktor->Exportable) $Doc->ExportCaption($this->jml_faktor);
				} else {
					if ($this->process_id->Exportable) $Doc->ExportCaption($this->process_id);
					if ($this->no_urut->Exportable) $Doc->ExportCaption($this->no_urut);
					if ($this->no_urut_ref->Exportable) $Doc->ExportCaption($this->no_urut_ref);
					if ($this->emp_id_auto->Exportable) $Doc->ExportCaption($this->emp_id_auto);
					if ($this->com_id->Exportable) $Doc->ExportCaption($this->com_id);
					if ($this->kondisi->Exportable) $Doc->ExportCaption($this->kondisi);
					if ($this->rumus->Exportable) $Doc->ExportCaption($this->rumus);
					if ($this->subtot_payroll->Exportable) $Doc->ExportCaption($this->subtot_payroll);
					if ($this->jml_faktor->Exportable) $Doc->ExportCaption($this->jml_faktor);
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
						if ($this->process_id->Exportable) $Doc->ExportField($this->process_id);
						if ($this->no_urut->Exportable) $Doc->ExportField($this->no_urut);
						if ($this->no_urut_ref->Exportable) $Doc->ExportField($this->no_urut_ref);
						if ($this->emp_id_auto->Exportable) $Doc->ExportField($this->emp_id_auto);
						if ($this->com_id->Exportable) $Doc->ExportField($this->com_id);
						if ($this->kondisi->Exportable) $Doc->ExportField($this->kondisi);
						if ($this->rumus->Exportable) $Doc->ExportField($this->rumus);
						if ($this->subtot_payroll->Exportable) $Doc->ExportField($this->subtot_payroll);
						if ($this->jml_faktor->Exportable) $Doc->ExportField($this->jml_faktor);
					} else {
						if ($this->process_id->Exportable) $Doc->ExportField($this->process_id);
						if ($this->no_urut->Exportable) $Doc->ExportField($this->no_urut);
						if ($this->no_urut_ref->Exportable) $Doc->ExportField($this->no_urut_ref);
						if ($this->emp_id_auto->Exportable) $Doc->ExportField($this->emp_id_auto);
						if ($this->com_id->Exportable) $Doc->ExportField($this->com_id);
						if ($this->kondisi->Exportable) $Doc->ExportField($this->kondisi);
						if ($this->rumus->Exportable) $Doc->ExportField($this->rumus);
						if ($this->subtot_payroll->Exportable) $Doc->ExportField($this->subtot_payroll);
						if ($this->jml_faktor->Exportable) $Doc->ExportField($this->jml_faktor);
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
