<?php

// Global variable for table object
$ganti_jdw_pembagian = NULL;

//
// Table class for ganti_jdw_pembagian
//
class cganti_jdw_pembagian extends cTable {
	var $ganti_jdw_id;
	var $pembagian1_id;
	var $pembagian2_id;
	var $pembagian3_id;
	var $tgl_awal;
	var $tgl_akhir;
	var $jdw_kerja_m_id;
	var $keterangan;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'ganti_jdw_pembagian';
		$this->TableName = 'ganti_jdw_pembagian';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`ganti_jdw_pembagian`";
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

		// ganti_jdw_id
		$this->ganti_jdw_id = new cField('ganti_jdw_pembagian', 'ganti_jdw_pembagian', 'x_ganti_jdw_id', 'ganti_jdw_id', '`ganti_jdw_id`', '`ganti_jdw_id`', 3, -1, FALSE, '`ganti_jdw_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->ganti_jdw_id->Sortable = TRUE; // Allow sort
		$this->ganti_jdw_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ganti_jdw_id'] = &$this->ganti_jdw_id;

		// pembagian1_id
		$this->pembagian1_id = new cField('ganti_jdw_pembagian', 'ganti_jdw_pembagian', 'x_pembagian1_id', 'pembagian1_id', '`pembagian1_id`', '`pembagian1_id`', 3, -1, FALSE, '`pembagian1_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pembagian1_id->Sortable = TRUE; // Allow sort
		$this->pembagian1_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['pembagian1_id'] = &$this->pembagian1_id;

		// pembagian2_id
		$this->pembagian2_id = new cField('ganti_jdw_pembagian', 'ganti_jdw_pembagian', 'x_pembagian2_id', 'pembagian2_id', '`pembagian2_id`', '`pembagian2_id`', 3, -1, FALSE, '`pembagian2_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pembagian2_id->Sortable = TRUE; // Allow sort
		$this->pembagian2_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['pembagian2_id'] = &$this->pembagian2_id;

		// pembagian3_id
		$this->pembagian3_id = new cField('ganti_jdw_pembagian', 'ganti_jdw_pembagian', 'x_pembagian3_id', 'pembagian3_id', '`pembagian3_id`', '`pembagian3_id`', 3, -1, FALSE, '`pembagian3_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pembagian3_id->Sortable = TRUE; // Allow sort
		$this->pembagian3_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['pembagian3_id'] = &$this->pembagian3_id;

		// tgl_awal
		$this->tgl_awal = new cField('ganti_jdw_pembagian', 'ganti_jdw_pembagian', 'x_tgl_awal', 'tgl_awal', '`tgl_awal`', ew_CastDateFieldForLike('`tgl_awal`', 0, "DB"), 133, 0, FALSE, '`tgl_awal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tgl_awal->Sortable = TRUE; // Allow sort
		$this->tgl_awal->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['tgl_awal'] = &$this->tgl_awal;

		// tgl_akhir
		$this->tgl_akhir = new cField('ganti_jdw_pembagian', 'ganti_jdw_pembagian', 'x_tgl_akhir', 'tgl_akhir', '`tgl_akhir`', ew_CastDateFieldForLike('`tgl_akhir`', 0, "DB"), 133, 0, FALSE, '`tgl_akhir`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tgl_akhir->Sortable = TRUE; // Allow sort
		$this->tgl_akhir->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['tgl_akhir'] = &$this->tgl_akhir;

		// jdw_kerja_m_id
		$this->jdw_kerja_m_id = new cField('ganti_jdw_pembagian', 'ganti_jdw_pembagian', 'x_jdw_kerja_m_id', 'jdw_kerja_m_id', '`jdw_kerja_m_id`', '`jdw_kerja_m_id`', 3, -1, FALSE, '`jdw_kerja_m_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jdw_kerja_m_id->Sortable = TRUE; // Allow sort
		$this->jdw_kerja_m_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['jdw_kerja_m_id'] = &$this->jdw_kerja_m_id;

		// keterangan
		$this->keterangan = new cField('ganti_jdw_pembagian', 'ganti_jdw_pembagian', 'x_keterangan', 'keterangan', '`keterangan`', '`keterangan`', 200, -1, FALSE, '`keterangan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->keterangan->Sortable = TRUE; // Allow sort
		$this->fields['keterangan'] = &$this->keterangan;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`ganti_jdw_pembagian`";
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
			if (array_key_exists('ganti_jdw_id', $rs))
				ew_AddFilter($where, ew_QuotedName('ganti_jdw_id', $this->DBID) . '=' . ew_QuotedValue($rs['ganti_jdw_id'], $this->ganti_jdw_id->FldDataType, $this->DBID));
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
		return "`ganti_jdw_id` = @ganti_jdw_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->ganti_jdw_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@ganti_jdw_id@", ew_AdjustSql($this->ganti_jdw_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "ganti_jdw_pembagianlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "ganti_jdw_pembagianlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("ganti_jdw_pembagianview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("ganti_jdw_pembagianview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "ganti_jdw_pembagianadd.php?" . $this->UrlParm($parm);
		else
			$url = "ganti_jdw_pembagianadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("ganti_jdw_pembagianedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("ganti_jdw_pembagianadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("ganti_jdw_pembagiandelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "ganti_jdw_id:" . ew_VarToJson($this->ganti_jdw_id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->ganti_jdw_id->CurrentValue)) {
			$sUrl .= "ganti_jdw_id=" . urlencode($this->ganti_jdw_id->CurrentValue);
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
			if ($isPost && isset($_POST["ganti_jdw_id"]))
				$arKeys[] = ew_StripSlashes($_POST["ganti_jdw_id"]);
			elseif (isset($_GET["ganti_jdw_id"]))
				$arKeys[] = ew_StripSlashes($_GET["ganti_jdw_id"]);
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
			$this->ganti_jdw_id->CurrentValue = $key;
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
		$this->ganti_jdw_id->setDbValue($rs->fields('ganti_jdw_id'));
		$this->pembagian1_id->setDbValue($rs->fields('pembagian1_id'));
		$this->pembagian2_id->setDbValue($rs->fields('pembagian2_id'));
		$this->pembagian3_id->setDbValue($rs->fields('pembagian3_id'));
		$this->tgl_awal->setDbValue($rs->fields('tgl_awal'));
		$this->tgl_akhir->setDbValue($rs->fields('tgl_akhir'));
		$this->jdw_kerja_m_id->setDbValue($rs->fields('jdw_kerja_m_id'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// ganti_jdw_id
		// pembagian1_id
		// pembagian2_id
		// pembagian3_id
		// tgl_awal
		// tgl_akhir
		// jdw_kerja_m_id
		// keterangan
		// ganti_jdw_id

		$this->ganti_jdw_id->ViewValue = $this->ganti_jdw_id->CurrentValue;
		$this->ganti_jdw_id->ViewCustomAttributes = "";

		// pembagian1_id
		$this->pembagian1_id->ViewValue = $this->pembagian1_id->CurrentValue;
		$this->pembagian1_id->ViewCustomAttributes = "";

		// pembagian2_id
		$this->pembagian2_id->ViewValue = $this->pembagian2_id->CurrentValue;
		$this->pembagian2_id->ViewCustomAttributes = "";

		// pembagian3_id
		$this->pembagian3_id->ViewValue = $this->pembagian3_id->CurrentValue;
		$this->pembagian3_id->ViewCustomAttributes = "";

		// tgl_awal
		$this->tgl_awal->ViewValue = $this->tgl_awal->CurrentValue;
		$this->tgl_awal->ViewValue = ew_FormatDateTime($this->tgl_awal->ViewValue, 0);
		$this->tgl_awal->ViewCustomAttributes = "";

		// tgl_akhir
		$this->tgl_akhir->ViewValue = $this->tgl_akhir->CurrentValue;
		$this->tgl_akhir->ViewValue = ew_FormatDateTime($this->tgl_akhir->ViewValue, 0);
		$this->tgl_akhir->ViewCustomAttributes = "";

		// jdw_kerja_m_id
		$this->jdw_kerja_m_id->ViewValue = $this->jdw_kerja_m_id->CurrentValue;
		$this->jdw_kerja_m_id->ViewCustomAttributes = "";

		// keterangan
		$this->keterangan->ViewValue = $this->keterangan->CurrentValue;
		$this->keterangan->ViewCustomAttributes = "";

		// ganti_jdw_id
		$this->ganti_jdw_id->LinkCustomAttributes = "";
		$this->ganti_jdw_id->HrefValue = "";
		$this->ganti_jdw_id->TooltipValue = "";

		// pembagian1_id
		$this->pembagian1_id->LinkCustomAttributes = "";
		$this->pembagian1_id->HrefValue = "";
		$this->pembagian1_id->TooltipValue = "";

		// pembagian2_id
		$this->pembagian2_id->LinkCustomAttributes = "";
		$this->pembagian2_id->HrefValue = "";
		$this->pembagian2_id->TooltipValue = "";

		// pembagian3_id
		$this->pembagian3_id->LinkCustomAttributes = "";
		$this->pembagian3_id->HrefValue = "";
		$this->pembagian3_id->TooltipValue = "";

		// tgl_awal
		$this->tgl_awal->LinkCustomAttributes = "";
		$this->tgl_awal->HrefValue = "";
		$this->tgl_awal->TooltipValue = "";

		// tgl_akhir
		$this->tgl_akhir->LinkCustomAttributes = "";
		$this->tgl_akhir->HrefValue = "";
		$this->tgl_akhir->TooltipValue = "";

		// jdw_kerja_m_id
		$this->jdw_kerja_m_id->LinkCustomAttributes = "";
		$this->jdw_kerja_m_id->HrefValue = "";
		$this->jdw_kerja_m_id->TooltipValue = "";

		// keterangan
		$this->keterangan->LinkCustomAttributes = "";
		$this->keterangan->HrefValue = "";
		$this->keterangan->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// ganti_jdw_id
		$this->ganti_jdw_id->EditAttrs["class"] = "form-control";
		$this->ganti_jdw_id->EditCustomAttributes = "";
		$this->ganti_jdw_id->EditValue = $this->ganti_jdw_id->CurrentValue;
		$this->ganti_jdw_id->ViewCustomAttributes = "";

		// pembagian1_id
		$this->pembagian1_id->EditAttrs["class"] = "form-control";
		$this->pembagian1_id->EditCustomAttributes = "";
		$this->pembagian1_id->EditValue = $this->pembagian1_id->CurrentValue;
		$this->pembagian1_id->PlaceHolder = ew_RemoveHtml($this->pembagian1_id->FldCaption());

		// pembagian2_id
		$this->pembagian2_id->EditAttrs["class"] = "form-control";
		$this->pembagian2_id->EditCustomAttributes = "";
		$this->pembagian2_id->EditValue = $this->pembagian2_id->CurrentValue;
		$this->pembagian2_id->PlaceHolder = ew_RemoveHtml($this->pembagian2_id->FldCaption());

		// pembagian3_id
		$this->pembagian3_id->EditAttrs["class"] = "form-control";
		$this->pembagian3_id->EditCustomAttributes = "";
		$this->pembagian3_id->EditValue = $this->pembagian3_id->CurrentValue;
		$this->pembagian3_id->PlaceHolder = ew_RemoveHtml($this->pembagian3_id->FldCaption());

		// tgl_awal
		$this->tgl_awal->EditAttrs["class"] = "form-control";
		$this->tgl_awal->EditCustomAttributes = "";
		$this->tgl_awal->EditValue = ew_FormatDateTime($this->tgl_awal->CurrentValue, 8);
		$this->tgl_awal->PlaceHolder = ew_RemoveHtml($this->tgl_awal->FldCaption());

		// tgl_akhir
		$this->tgl_akhir->EditAttrs["class"] = "form-control";
		$this->tgl_akhir->EditCustomAttributes = "";
		$this->tgl_akhir->EditValue = ew_FormatDateTime($this->tgl_akhir->CurrentValue, 8);
		$this->tgl_akhir->PlaceHolder = ew_RemoveHtml($this->tgl_akhir->FldCaption());

		// jdw_kerja_m_id
		$this->jdw_kerja_m_id->EditAttrs["class"] = "form-control";
		$this->jdw_kerja_m_id->EditCustomAttributes = "";
		$this->jdw_kerja_m_id->EditValue = $this->jdw_kerja_m_id->CurrentValue;
		$this->jdw_kerja_m_id->PlaceHolder = ew_RemoveHtml($this->jdw_kerja_m_id->FldCaption());

		// keterangan
		$this->keterangan->EditAttrs["class"] = "form-control";
		$this->keterangan->EditCustomAttributes = "";
		$this->keterangan->EditValue = $this->keterangan->CurrentValue;
		$this->keterangan->PlaceHolder = ew_RemoveHtml($this->keterangan->FldCaption());

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
					if ($this->ganti_jdw_id->Exportable) $Doc->ExportCaption($this->ganti_jdw_id);
					if ($this->pembagian1_id->Exportable) $Doc->ExportCaption($this->pembagian1_id);
					if ($this->pembagian2_id->Exportable) $Doc->ExportCaption($this->pembagian2_id);
					if ($this->pembagian3_id->Exportable) $Doc->ExportCaption($this->pembagian3_id);
					if ($this->tgl_awal->Exportable) $Doc->ExportCaption($this->tgl_awal);
					if ($this->tgl_akhir->Exportable) $Doc->ExportCaption($this->tgl_akhir);
					if ($this->jdw_kerja_m_id->Exportable) $Doc->ExportCaption($this->jdw_kerja_m_id);
					if ($this->keterangan->Exportable) $Doc->ExportCaption($this->keterangan);
				} else {
					if ($this->ganti_jdw_id->Exportable) $Doc->ExportCaption($this->ganti_jdw_id);
					if ($this->pembagian1_id->Exportable) $Doc->ExportCaption($this->pembagian1_id);
					if ($this->pembagian2_id->Exportable) $Doc->ExportCaption($this->pembagian2_id);
					if ($this->pembagian3_id->Exportable) $Doc->ExportCaption($this->pembagian3_id);
					if ($this->tgl_awal->Exportable) $Doc->ExportCaption($this->tgl_awal);
					if ($this->tgl_akhir->Exportable) $Doc->ExportCaption($this->tgl_akhir);
					if ($this->jdw_kerja_m_id->Exportable) $Doc->ExportCaption($this->jdw_kerja_m_id);
					if ($this->keterangan->Exportable) $Doc->ExportCaption($this->keterangan);
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
						if ($this->ganti_jdw_id->Exportable) $Doc->ExportField($this->ganti_jdw_id);
						if ($this->pembagian1_id->Exportable) $Doc->ExportField($this->pembagian1_id);
						if ($this->pembagian2_id->Exportable) $Doc->ExportField($this->pembagian2_id);
						if ($this->pembagian3_id->Exportable) $Doc->ExportField($this->pembagian3_id);
						if ($this->tgl_awal->Exportable) $Doc->ExportField($this->tgl_awal);
						if ($this->tgl_akhir->Exportable) $Doc->ExportField($this->tgl_akhir);
						if ($this->jdw_kerja_m_id->Exportable) $Doc->ExportField($this->jdw_kerja_m_id);
						if ($this->keterangan->Exportable) $Doc->ExportField($this->keterangan);
					} else {
						if ($this->ganti_jdw_id->Exportable) $Doc->ExportField($this->ganti_jdw_id);
						if ($this->pembagian1_id->Exportable) $Doc->ExportField($this->pembagian1_id);
						if ($this->pembagian2_id->Exportable) $Doc->ExportField($this->pembagian2_id);
						if ($this->pembagian3_id->Exportable) $Doc->ExportField($this->pembagian3_id);
						if ($this->tgl_awal->Exportable) $Doc->ExportField($this->tgl_awal);
						if ($this->tgl_akhir->Exportable) $Doc->ExportField($this->tgl_akhir);
						if ($this->jdw_kerja_m_id->Exportable) $Doc->ExportField($this->jdw_kerja_m_id);
						if ($this->keterangan->Exportable) $Doc->ExportField($this->keterangan);
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
