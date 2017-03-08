<?php

// Global variable for table object
$z_pay_money = NULL;

//
// Table class for z_pay_money
//
class cz_pay_money extends cTable {
	var $com_id;
	var $grp_id;
	var $emp_id_auto;
	var $nilai_rp;
	var $lastupdate_date;
	var $lastupdate_user;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'z_pay_money';
		$this->TableName = 'z_pay_money';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`z_pay_money`";
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

		// com_id
		$this->com_id = new cField('z_pay_money', 'z_pay_money', 'x_com_id', 'com_id', '`com_id`', '`com_id`', 2, -1, FALSE, '`com_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->com_id->Sortable = TRUE; // Allow sort
		$this->com_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['com_id'] = &$this->com_id;

		// grp_id
		$this->grp_id = new cField('z_pay_money', 'z_pay_money', 'x_grp_id', 'grp_id', '`grp_id`', '`grp_id`', 2, -1, FALSE, '`grp_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->grp_id->Sortable = TRUE; // Allow sort
		$this->grp_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['grp_id'] = &$this->grp_id;

		// emp_id_auto
		$this->emp_id_auto = new cField('z_pay_money', 'z_pay_money', 'x_emp_id_auto', 'emp_id_auto', '`emp_id_auto`', '`emp_id_auto`', 3, -1, FALSE, '`emp_id_auto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->emp_id_auto->Sortable = TRUE; // Allow sort
		$this->emp_id_auto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['emp_id_auto'] = &$this->emp_id_auto;

		// nilai_rp
		$this->nilai_rp = new cField('z_pay_money', 'z_pay_money', 'x_nilai_rp', 'nilai_rp', '`nilai_rp`', '`nilai_rp`', 4, -1, FALSE, '`nilai_rp`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nilai_rp->Sortable = TRUE; // Allow sort
		$this->nilai_rp->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['nilai_rp'] = &$this->nilai_rp;

		// lastupdate_date
		$this->lastupdate_date = new cField('z_pay_money', 'z_pay_money', 'x_lastupdate_date', 'lastupdate_date', '`lastupdate_date`', ew_CastDateFieldForLike('`lastupdate_date`', 0, "DB"), 135, 0, FALSE, '`lastupdate_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->lastupdate_date->Sortable = TRUE; // Allow sort
		$this->lastupdate_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['lastupdate_date'] = &$this->lastupdate_date;

		// lastupdate_user
		$this->lastupdate_user = new cField('z_pay_money', 'z_pay_money', 'x_lastupdate_user', 'lastupdate_user', '`lastupdate_user`', '`lastupdate_user`', 200, -1, FALSE, '`lastupdate_user`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`z_pay_money`";
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
			if (array_key_exists('com_id', $rs))
				ew_AddFilter($where, ew_QuotedName('com_id', $this->DBID) . '=' . ew_QuotedValue($rs['com_id'], $this->com_id->FldDataType, $this->DBID));
			if (array_key_exists('grp_id', $rs))
				ew_AddFilter($where, ew_QuotedName('grp_id', $this->DBID) . '=' . ew_QuotedValue($rs['grp_id'], $this->grp_id->FldDataType, $this->DBID));
			if (array_key_exists('emp_id_auto', $rs))
				ew_AddFilter($where, ew_QuotedName('emp_id_auto', $this->DBID) . '=' . ew_QuotedValue($rs['emp_id_auto'], $this->emp_id_auto->FldDataType, $this->DBID));
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
		return "`com_id` = @com_id@ AND `grp_id` = @grp_id@ AND `emp_id_auto` = @emp_id_auto@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->com_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@com_id@", ew_AdjustSql($this->com_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		if (!is_numeric($this->grp_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@grp_id@", ew_AdjustSql($this->grp_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		if (!is_numeric($this->emp_id_auto->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@emp_id_auto@", ew_AdjustSql($this->emp_id_auto->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "z_pay_moneylist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "z_pay_moneylist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("z_pay_moneyview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("z_pay_moneyview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "z_pay_moneyadd.php?" . $this->UrlParm($parm);
		else
			$url = "z_pay_moneyadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("z_pay_moneyedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("z_pay_moneyadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("z_pay_moneydelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "com_id:" . ew_VarToJson($this->com_id->CurrentValue, "number", "'");
		$json .= ",grp_id:" . ew_VarToJson($this->grp_id->CurrentValue, "number", "'");
		$json .= ",emp_id_auto:" . ew_VarToJson($this->emp_id_auto->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->com_id->CurrentValue)) {
			$sUrl .= "com_id=" . urlencode($this->com_id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->grp_id->CurrentValue)) {
			$sUrl .= "&grp_id=" . urlencode($this->grp_id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->emp_id_auto->CurrentValue)) {
			$sUrl .= "&emp_id_auto=" . urlencode($this->emp_id_auto->CurrentValue);
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
			if ($isPost && isset($_POST["com_id"]))
				$arKey[] = ew_StripSlashes($_POST["com_id"]);
			elseif (isset($_GET["com_id"]))
				$arKey[] = ew_StripSlashes($_GET["com_id"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["grp_id"]))
				$arKey[] = ew_StripSlashes($_POST["grp_id"]);
			elseif (isset($_GET["grp_id"]))
				$arKey[] = ew_StripSlashes($_GET["grp_id"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["emp_id_auto"]))
				$arKey[] = ew_StripSlashes($_POST["emp_id_auto"]);
			elseif (isset($_GET["emp_id_auto"]))
				$arKey[] = ew_StripSlashes($_GET["emp_id_auto"]);
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
				if (!is_numeric($key[0])) // com_id
					continue;
				if (!is_numeric($key[1])) // grp_id
					continue;
				if (!is_numeric($key[2])) // emp_id_auto
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
			$this->com_id->CurrentValue = $key[0];
			$this->grp_id->CurrentValue = $key[1];
			$this->emp_id_auto->CurrentValue = $key[2];
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
		$this->com_id->setDbValue($rs->fields('com_id'));
		$this->grp_id->setDbValue($rs->fields('grp_id'));
		$this->emp_id_auto->setDbValue($rs->fields('emp_id_auto'));
		$this->nilai_rp->setDbValue($rs->fields('nilai_rp'));
		$this->lastupdate_date->setDbValue($rs->fields('lastupdate_date'));
		$this->lastupdate_user->setDbValue($rs->fields('lastupdate_user'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// com_id
		// grp_id
		// emp_id_auto
		// nilai_rp
		// lastupdate_date
		// lastupdate_user
		// com_id

		$this->com_id->ViewValue = $this->com_id->CurrentValue;
		$this->com_id->ViewCustomAttributes = "";

		// grp_id
		$this->grp_id->ViewValue = $this->grp_id->CurrentValue;
		$this->grp_id->ViewCustomAttributes = "";

		// emp_id_auto
		$this->emp_id_auto->ViewValue = $this->emp_id_auto->CurrentValue;
		$this->emp_id_auto->ViewCustomAttributes = "";

		// nilai_rp
		$this->nilai_rp->ViewValue = $this->nilai_rp->CurrentValue;
		$this->nilai_rp->ViewCustomAttributes = "";

		// lastupdate_date
		$this->lastupdate_date->ViewValue = $this->lastupdate_date->CurrentValue;
		$this->lastupdate_date->ViewValue = ew_FormatDateTime($this->lastupdate_date->ViewValue, 0);
		$this->lastupdate_date->ViewCustomAttributes = "";

		// lastupdate_user
		$this->lastupdate_user->ViewValue = $this->lastupdate_user->CurrentValue;
		$this->lastupdate_user->ViewCustomAttributes = "";

		// com_id
		$this->com_id->LinkCustomAttributes = "";
		$this->com_id->HrefValue = "";
		$this->com_id->TooltipValue = "";

		// grp_id
		$this->grp_id->LinkCustomAttributes = "";
		$this->grp_id->HrefValue = "";
		$this->grp_id->TooltipValue = "";

		// emp_id_auto
		$this->emp_id_auto->LinkCustomAttributes = "";
		$this->emp_id_auto->HrefValue = "";
		$this->emp_id_auto->TooltipValue = "";

		// nilai_rp
		$this->nilai_rp->LinkCustomAttributes = "";
		$this->nilai_rp->HrefValue = "";
		$this->nilai_rp->TooltipValue = "";

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

		// com_id
		$this->com_id->EditAttrs["class"] = "form-control";
		$this->com_id->EditCustomAttributes = "";
		$this->com_id->EditValue = $this->com_id->CurrentValue;
		$this->com_id->ViewCustomAttributes = "";

		// grp_id
		$this->grp_id->EditAttrs["class"] = "form-control";
		$this->grp_id->EditCustomAttributes = "";
		$this->grp_id->EditValue = $this->grp_id->CurrentValue;
		$this->grp_id->ViewCustomAttributes = "";

		// emp_id_auto
		$this->emp_id_auto->EditAttrs["class"] = "form-control";
		$this->emp_id_auto->EditCustomAttributes = "";
		$this->emp_id_auto->EditValue = $this->emp_id_auto->CurrentValue;
		$this->emp_id_auto->ViewCustomAttributes = "";

		// nilai_rp
		$this->nilai_rp->EditAttrs["class"] = "form-control";
		$this->nilai_rp->EditCustomAttributes = "";
		$this->nilai_rp->EditValue = $this->nilai_rp->CurrentValue;
		$this->nilai_rp->PlaceHolder = ew_RemoveHtml($this->nilai_rp->FldCaption());
		if (strval($this->nilai_rp->EditValue) <> "" && is_numeric($this->nilai_rp->EditValue)) $this->nilai_rp->EditValue = ew_FormatNumber($this->nilai_rp->EditValue, -2, -1, -2, 0);

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
					if ($this->com_id->Exportable) $Doc->ExportCaption($this->com_id);
					if ($this->grp_id->Exportable) $Doc->ExportCaption($this->grp_id);
					if ($this->emp_id_auto->Exportable) $Doc->ExportCaption($this->emp_id_auto);
					if ($this->nilai_rp->Exportable) $Doc->ExportCaption($this->nilai_rp);
					if ($this->lastupdate_date->Exportable) $Doc->ExportCaption($this->lastupdate_date);
					if ($this->lastupdate_user->Exportable) $Doc->ExportCaption($this->lastupdate_user);
				} else {
					if ($this->com_id->Exportable) $Doc->ExportCaption($this->com_id);
					if ($this->grp_id->Exportable) $Doc->ExportCaption($this->grp_id);
					if ($this->emp_id_auto->Exportable) $Doc->ExportCaption($this->emp_id_auto);
					if ($this->nilai_rp->Exportable) $Doc->ExportCaption($this->nilai_rp);
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
						if ($this->com_id->Exportable) $Doc->ExportField($this->com_id);
						if ($this->grp_id->Exportable) $Doc->ExportField($this->grp_id);
						if ($this->emp_id_auto->Exportable) $Doc->ExportField($this->emp_id_auto);
						if ($this->nilai_rp->Exportable) $Doc->ExportField($this->nilai_rp);
						if ($this->lastupdate_date->Exportable) $Doc->ExportField($this->lastupdate_date);
						if ($this->lastupdate_user->Exportable) $Doc->ExportField($this->lastupdate_user);
					} else {
						if ($this->com_id->Exportable) $Doc->ExportField($this->com_id);
						if ($this->grp_id->Exportable) $Doc->ExportField($this->grp_id);
						if ($this->emp_id_auto->Exportable) $Doc->ExportField($this->emp_id_auto);
						if ($this->nilai_rp->Exportable) $Doc->ExportField($this->nilai_rp);
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
