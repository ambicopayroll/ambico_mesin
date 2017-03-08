<?php

// Global variable for table object
$z_pay_process_m = NULL;

//
// Table class for z_pay_process_m
//
class cz_pay_process_m extends cTable {
	var $process_id;
	var $process_name;
	var $date1;
	var $date2;
	var $payment_date;
	var $round_value;
	var $tot_process;
	var $create_by;
	var $check_by;
	var $approve_by;
	var $keterangan;
	var $lastupdate_date;
	var $lastupdate_user;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'z_pay_process_m';
		$this->TableName = 'z_pay_process_m';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`z_pay_process_m`";
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
		$this->process_id = new cField('z_pay_process_m', 'z_pay_process_m', 'x_process_id', 'process_id', '`process_id`', '`process_id`', 3, -1, FALSE, '`process_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->process_id->Sortable = TRUE; // Allow sort
		$this->process_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['process_id'] = &$this->process_id;

		// process_name
		$this->process_name = new cField('z_pay_process_m', 'z_pay_process_m', 'x_process_name', 'process_name', '`process_name`', '`process_name`', 200, -1, FALSE, '`process_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->process_name->Sortable = TRUE; // Allow sort
		$this->fields['process_name'] = &$this->process_name;

		// date1
		$this->date1 = new cField('z_pay_process_m', 'z_pay_process_m', 'x_date1', 'date1', '`date1`', ew_CastDateFieldForLike('`date1`', 0, "DB"), 133, 0, FALSE, '`date1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->date1->Sortable = TRUE; // Allow sort
		$this->date1->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['date1'] = &$this->date1;

		// date2
		$this->date2 = new cField('z_pay_process_m', 'z_pay_process_m', 'x_date2', 'date2', '`date2`', ew_CastDateFieldForLike('`date2`', 0, "DB"), 133, 0, FALSE, '`date2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->date2->Sortable = TRUE; // Allow sort
		$this->date2->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['date2'] = &$this->date2;

		// payment_date
		$this->payment_date = new cField('z_pay_process_m', 'z_pay_process_m', 'x_payment_date', 'payment_date', '`payment_date`', ew_CastDateFieldForLike('`payment_date`', 0, "DB"), 133, 0, FALSE, '`payment_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->payment_date->Sortable = TRUE; // Allow sort
		$this->payment_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['payment_date'] = &$this->payment_date;

		// round_value
		$this->round_value = new cField('z_pay_process_m', 'z_pay_process_m', 'x_round_value', 'round_value', '`round_value`', '`round_value`', 4, -1, FALSE, '`round_value`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->round_value->Sortable = TRUE; // Allow sort
		$this->round_value->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['round_value'] = &$this->round_value;

		// tot_process
		$this->tot_process = new cField('z_pay_process_m', 'z_pay_process_m', 'x_tot_process', 'tot_process', '`tot_process`', '`tot_process`', 4, -1, FALSE, '`tot_process`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tot_process->Sortable = TRUE; // Allow sort
		$this->tot_process->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['tot_process'] = &$this->tot_process;

		// create_by
		$this->create_by = new cField('z_pay_process_m', 'z_pay_process_m', 'x_create_by', 'create_by', '`create_by`', '`create_by`', 200, -1, FALSE, '`create_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->create_by->Sortable = TRUE; // Allow sort
		$this->fields['create_by'] = &$this->create_by;

		// check_by
		$this->check_by = new cField('z_pay_process_m', 'z_pay_process_m', 'x_check_by', 'check_by', '`check_by`', '`check_by`', 200, -1, FALSE, '`check_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->check_by->Sortable = TRUE; // Allow sort
		$this->fields['check_by'] = &$this->check_by;

		// approve_by
		$this->approve_by = new cField('z_pay_process_m', 'z_pay_process_m', 'x_approve_by', 'approve_by', '`approve_by`', '`approve_by`', 200, -1, FALSE, '`approve_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->approve_by->Sortable = TRUE; // Allow sort
		$this->fields['approve_by'] = &$this->approve_by;

		// keterangan
		$this->keterangan = new cField('z_pay_process_m', 'z_pay_process_m', 'x_keterangan', 'keterangan', '`keterangan`', '`keterangan`', 200, -1, FALSE, '`keterangan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->keterangan->Sortable = TRUE; // Allow sort
		$this->fields['keterangan'] = &$this->keterangan;

		// lastupdate_date
		$this->lastupdate_date = new cField('z_pay_process_m', 'z_pay_process_m', 'x_lastupdate_date', 'lastupdate_date', '`lastupdate_date`', ew_CastDateFieldForLike('`lastupdate_date`', 0, "DB"), 135, 0, FALSE, '`lastupdate_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->lastupdate_date->Sortable = TRUE; // Allow sort
		$this->lastupdate_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['lastupdate_date'] = &$this->lastupdate_date;

		// lastupdate_user
		$this->lastupdate_user = new cField('z_pay_process_m', 'z_pay_process_m', 'x_lastupdate_user', 'lastupdate_user', '`lastupdate_user`', '`lastupdate_user`', 200, -1, FALSE, '`lastupdate_user`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->lastupdate_user->Sortable = TRUE; // Allow sort
		$this->fields['lastupdate_user'] = &$this->lastupdate_user;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`z_pay_process_m`";
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
			if (array_key_exists('process_id', $rs))
				ew_AddFilter($where, ew_QuotedName('process_id', $this->DBID) . '=' . ew_QuotedValue($rs['process_id'], $this->process_id->FldDataType, $this->DBID));
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
		return "`process_id` = @process_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->process_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@process_id@", ew_AdjustSql($this->process_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "z_pay_process_mlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "z_pay_process_mlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("z_pay_process_mview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("z_pay_process_mview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "z_pay_process_madd.php?" . $this->UrlParm($parm);
		else
			$url = "z_pay_process_madd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("z_pay_process_medit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("z_pay_process_madd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("z_pay_process_mdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "process_id:" . ew_VarToJson($this->process_id->CurrentValue, "number", "'");
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
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["process_id"]))
				$arKeys[] = ew_StripSlashes($_POST["process_id"]);
			elseif (isset($_GET["process_id"]))
				$arKeys[] = ew_StripSlashes($_GET["process_id"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
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
			$this->process_id->CurrentValue = $key;
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
		$this->process_name->setDbValue($rs->fields('process_name'));
		$this->date1->setDbValue($rs->fields('date1'));
		$this->date2->setDbValue($rs->fields('date2'));
		$this->payment_date->setDbValue($rs->fields('payment_date'));
		$this->round_value->setDbValue($rs->fields('round_value'));
		$this->tot_process->setDbValue($rs->fields('tot_process'));
		$this->create_by->setDbValue($rs->fields('create_by'));
		$this->check_by->setDbValue($rs->fields('check_by'));
		$this->approve_by->setDbValue($rs->fields('approve_by'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
		$this->lastupdate_date->setDbValue($rs->fields('lastupdate_date'));
		$this->lastupdate_user->setDbValue($rs->fields('lastupdate_user'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// process_id
		// process_name
		// date1
		// date2
		// payment_date
		// round_value
		// tot_process
		// create_by
		// check_by
		// approve_by
		// keterangan
		// lastupdate_date
		// lastupdate_user
		// process_id

		$this->process_id->ViewValue = $this->process_id->CurrentValue;
		$this->process_id->ViewCustomAttributes = "";

		// process_name
		$this->process_name->ViewValue = $this->process_name->CurrentValue;
		$this->process_name->ViewCustomAttributes = "";

		// date1
		$this->date1->ViewValue = $this->date1->CurrentValue;
		$this->date1->ViewValue = ew_FormatDateTime($this->date1->ViewValue, 0);
		$this->date1->ViewCustomAttributes = "";

		// date2
		$this->date2->ViewValue = $this->date2->CurrentValue;
		$this->date2->ViewValue = ew_FormatDateTime($this->date2->ViewValue, 0);
		$this->date2->ViewCustomAttributes = "";

		// payment_date
		$this->payment_date->ViewValue = $this->payment_date->CurrentValue;
		$this->payment_date->ViewValue = ew_FormatDateTime($this->payment_date->ViewValue, 0);
		$this->payment_date->ViewCustomAttributes = "";

		// round_value
		$this->round_value->ViewValue = $this->round_value->CurrentValue;
		$this->round_value->ViewCustomAttributes = "";

		// tot_process
		$this->tot_process->ViewValue = $this->tot_process->CurrentValue;
		$this->tot_process->ViewCustomAttributes = "";

		// create_by
		$this->create_by->ViewValue = $this->create_by->CurrentValue;
		$this->create_by->ViewCustomAttributes = "";

		// check_by
		$this->check_by->ViewValue = $this->check_by->CurrentValue;
		$this->check_by->ViewCustomAttributes = "";

		// approve_by
		$this->approve_by->ViewValue = $this->approve_by->CurrentValue;
		$this->approve_by->ViewCustomAttributes = "";

		// keterangan
		$this->keterangan->ViewValue = $this->keterangan->CurrentValue;
		$this->keterangan->ViewCustomAttributes = "";

		// lastupdate_date
		$this->lastupdate_date->ViewValue = $this->lastupdate_date->CurrentValue;
		$this->lastupdate_date->ViewValue = ew_FormatDateTime($this->lastupdate_date->ViewValue, 0);
		$this->lastupdate_date->ViewCustomAttributes = "";

		// lastupdate_user
		$this->lastupdate_user->ViewValue = $this->lastupdate_user->CurrentValue;
		$this->lastupdate_user->ViewCustomAttributes = "";

		// process_id
		$this->process_id->LinkCustomAttributes = "";
		$this->process_id->HrefValue = "";
		$this->process_id->TooltipValue = "";

		// process_name
		$this->process_name->LinkCustomAttributes = "";
		$this->process_name->HrefValue = "";
		$this->process_name->TooltipValue = "";

		// date1
		$this->date1->LinkCustomAttributes = "";
		$this->date1->HrefValue = "";
		$this->date1->TooltipValue = "";

		// date2
		$this->date2->LinkCustomAttributes = "";
		$this->date2->HrefValue = "";
		$this->date2->TooltipValue = "";

		// payment_date
		$this->payment_date->LinkCustomAttributes = "";
		$this->payment_date->HrefValue = "";
		$this->payment_date->TooltipValue = "";

		// round_value
		$this->round_value->LinkCustomAttributes = "";
		$this->round_value->HrefValue = "";
		$this->round_value->TooltipValue = "";

		// tot_process
		$this->tot_process->LinkCustomAttributes = "";
		$this->tot_process->HrefValue = "";
		$this->tot_process->TooltipValue = "";

		// create_by
		$this->create_by->LinkCustomAttributes = "";
		$this->create_by->HrefValue = "";
		$this->create_by->TooltipValue = "";

		// check_by
		$this->check_by->LinkCustomAttributes = "";
		$this->check_by->HrefValue = "";
		$this->check_by->TooltipValue = "";

		// approve_by
		$this->approve_by->LinkCustomAttributes = "";
		$this->approve_by->HrefValue = "";
		$this->approve_by->TooltipValue = "";

		// keterangan
		$this->keterangan->LinkCustomAttributes = "";
		$this->keterangan->HrefValue = "";
		$this->keterangan->TooltipValue = "";

		// lastupdate_date
		$this->lastupdate_date->LinkCustomAttributes = "";
		$this->lastupdate_date->HrefValue = "";
		$this->lastupdate_date->TooltipValue = "";

		// lastupdate_user
		$this->lastupdate_user->LinkCustomAttributes = "";
		$this->lastupdate_user->HrefValue = "";
		$this->lastupdate_user->TooltipValue = "";

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

		// process_name
		$this->process_name->EditAttrs["class"] = "form-control";
		$this->process_name->EditCustomAttributes = "";
		$this->process_name->EditValue = $this->process_name->CurrentValue;
		$this->process_name->PlaceHolder = ew_RemoveHtml($this->process_name->FldCaption());

		// date1
		$this->date1->EditAttrs["class"] = "form-control";
		$this->date1->EditCustomAttributes = "";
		$this->date1->EditValue = ew_FormatDateTime($this->date1->CurrentValue, 8);
		$this->date1->PlaceHolder = ew_RemoveHtml($this->date1->FldCaption());

		// date2
		$this->date2->EditAttrs["class"] = "form-control";
		$this->date2->EditCustomAttributes = "";
		$this->date2->EditValue = ew_FormatDateTime($this->date2->CurrentValue, 8);
		$this->date2->PlaceHolder = ew_RemoveHtml($this->date2->FldCaption());

		// payment_date
		$this->payment_date->EditAttrs["class"] = "form-control";
		$this->payment_date->EditCustomAttributes = "";
		$this->payment_date->EditValue = ew_FormatDateTime($this->payment_date->CurrentValue, 8);
		$this->payment_date->PlaceHolder = ew_RemoveHtml($this->payment_date->FldCaption());

		// round_value
		$this->round_value->EditAttrs["class"] = "form-control";
		$this->round_value->EditCustomAttributes = "";
		$this->round_value->EditValue = $this->round_value->CurrentValue;
		$this->round_value->PlaceHolder = ew_RemoveHtml($this->round_value->FldCaption());
		if (strval($this->round_value->EditValue) <> "" && is_numeric($this->round_value->EditValue)) $this->round_value->EditValue = ew_FormatNumber($this->round_value->EditValue, -2, -1, -2, 0);

		// tot_process
		$this->tot_process->EditAttrs["class"] = "form-control";
		$this->tot_process->EditCustomAttributes = "";
		$this->tot_process->EditValue = $this->tot_process->CurrentValue;
		$this->tot_process->PlaceHolder = ew_RemoveHtml($this->tot_process->FldCaption());
		if (strval($this->tot_process->EditValue) <> "" && is_numeric($this->tot_process->EditValue)) $this->tot_process->EditValue = ew_FormatNumber($this->tot_process->EditValue, -2, -1, -2, 0);

		// create_by
		$this->create_by->EditAttrs["class"] = "form-control";
		$this->create_by->EditCustomAttributes = "";
		$this->create_by->EditValue = $this->create_by->CurrentValue;
		$this->create_by->PlaceHolder = ew_RemoveHtml($this->create_by->FldCaption());

		// check_by
		$this->check_by->EditAttrs["class"] = "form-control";
		$this->check_by->EditCustomAttributes = "";
		$this->check_by->EditValue = $this->check_by->CurrentValue;
		$this->check_by->PlaceHolder = ew_RemoveHtml($this->check_by->FldCaption());

		// approve_by
		$this->approve_by->EditAttrs["class"] = "form-control";
		$this->approve_by->EditCustomAttributes = "";
		$this->approve_by->EditValue = $this->approve_by->CurrentValue;
		$this->approve_by->PlaceHolder = ew_RemoveHtml($this->approve_by->FldCaption());

		// keterangan
		$this->keterangan->EditAttrs["class"] = "form-control";
		$this->keterangan->EditCustomAttributes = "";
		$this->keterangan->EditValue = $this->keterangan->CurrentValue;
		$this->keterangan->PlaceHolder = ew_RemoveHtml($this->keterangan->FldCaption());

		// lastupdate_date
		$this->lastupdate_date->EditAttrs["class"] = "form-control";
		$this->lastupdate_date->EditCustomAttributes = "";
		$this->lastupdate_date->EditValue = ew_FormatDateTime($this->lastupdate_date->CurrentValue, 8);
		$this->lastupdate_date->PlaceHolder = ew_RemoveHtml($this->lastupdate_date->FldCaption());

		// lastupdate_user
		$this->lastupdate_user->EditAttrs["class"] = "form-control";
		$this->lastupdate_user->EditCustomAttributes = "";
		$this->lastupdate_user->EditValue = $this->lastupdate_user->CurrentValue;
		$this->lastupdate_user->PlaceHolder = ew_RemoveHtml($this->lastupdate_user->FldCaption());

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
					if ($this->process_name->Exportable) $Doc->ExportCaption($this->process_name);
					if ($this->date1->Exportable) $Doc->ExportCaption($this->date1);
					if ($this->date2->Exportable) $Doc->ExportCaption($this->date2);
					if ($this->payment_date->Exportable) $Doc->ExportCaption($this->payment_date);
					if ($this->round_value->Exportable) $Doc->ExportCaption($this->round_value);
					if ($this->tot_process->Exportable) $Doc->ExportCaption($this->tot_process);
					if ($this->create_by->Exportable) $Doc->ExportCaption($this->create_by);
					if ($this->check_by->Exportable) $Doc->ExportCaption($this->check_by);
					if ($this->approve_by->Exportable) $Doc->ExportCaption($this->approve_by);
					if ($this->keterangan->Exportable) $Doc->ExportCaption($this->keterangan);
					if ($this->lastupdate_date->Exportable) $Doc->ExportCaption($this->lastupdate_date);
					if ($this->lastupdate_user->Exportable) $Doc->ExportCaption($this->lastupdate_user);
				} else {
					if ($this->process_id->Exportable) $Doc->ExportCaption($this->process_id);
					if ($this->process_name->Exportable) $Doc->ExportCaption($this->process_name);
					if ($this->date1->Exportable) $Doc->ExportCaption($this->date1);
					if ($this->date2->Exportable) $Doc->ExportCaption($this->date2);
					if ($this->payment_date->Exportable) $Doc->ExportCaption($this->payment_date);
					if ($this->round_value->Exportable) $Doc->ExportCaption($this->round_value);
					if ($this->tot_process->Exportable) $Doc->ExportCaption($this->tot_process);
					if ($this->create_by->Exportable) $Doc->ExportCaption($this->create_by);
					if ($this->check_by->Exportable) $Doc->ExportCaption($this->check_by);
					if ($this->approve_by->Exportable) $Doc->ExportCaption($this->approve_by);
					if ($this->keterangan->Exportable) $Doc->ExportCaption($this->keterangan);
					if ($this->lastupdate_date->Exportable) $Doc->ExportCaption($this->lastupdate_date);
					if ($this->lastupdate_user->Exportable) $Doc->ExportCaption($this->lastupdate_user);
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
						if ($this->process_name->Exportable) $Doc->ExportField($this->process_name);
						if ($this->date1->Exportable) $Doc->ExportField($this->date1);
						if ($this->date2->Exportable) $Doc->ExportField($this->date2);
						if ($this->payment_date->Exportable) $Doc->ExportField($this->payment_date);
						if ($this->round_value->Exportable) $Doc->ExportField($this->round_value);
						if ($this->tot_process->Exportable) $Doc->ExportField($this->tot_process);
						if ($this->create_by->Exportable) $Doc->ExportField($this->create_by);
						if ($this->check_by->Exportable) $Doc->ExportField($this->check_by);
						if ($this->approve_by->Exportable) $Doc->ExportField($this->approve_by);
						if ($this->keterangan->Exportable) $Doc->ExportField($this->keterangan);
						if ($this->lastupdate_date->Exportable) $Doc->ExportField($this->lastupdate_date);
						if ($this->lastupdate_user->Exportable) $Doc->ExportField($this->lastupdate_user);
					} else {
						if ($this->process_id->Exportable) $Doc->ExportField($this->process_id);
						if ($this->process_name->Exportable) $Doc->ExportField($this->process_name);
						if ($this->date1->Exportable) $Doc->ExportField($this->date1);
						if ($this->date2->Exportable) $Doc->ExportField($this->date2);
						if ($this->payment_date->Exportable) $Doc->ExportField($this->payment_date);
						if ($this->round_value->Exportable) $Doc->ExportField($this->round_value);
						if ($this->tot_process->Exportable) $Doc->ExportField($this->tot_process);
						if ($this->create_by->Exportable) $Doc->ExportField($this->create_by);
						if ($this->check_by->Exportable) $Doc->ExportField($this->check_by);
						if ($this->approve_by->Exportable) $Doc->ExportField($this->approve_by);
						if ($this->keterangan->Exportable) $Doc->ExportField($this->keterangan);
						if ($this->lastupdate_date->Exportable) $Doc->ExportField($this->lastupdate_date);
						if ($this->lastupdate_user->Exportable) $Doc->ExportField($this->lastupdate_user);
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
