<?php

// Global variable for table object
$jatah_cuti = NULL;

//
// Table class for jatah_cuti
//
class cjatah_cuti extends cTable {
	var $pegawai_id;
	var $jatah_c_mulai;
	var $jatah_c_akhir;
	var $jatah_c_jml;
	var $jatah_c_hak_jml;
	var $jatah_c_ambil_jml;
	var $jatah_c_utang_jml;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'jatah_cuti';
		$this->TableName = 'jatah_cuti';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`jatah_cuti`";
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
		$this->pegawai_id = new cField('jatah_cuti', 'jatah_cuti', 'x_pegawai_id', 'pegawai_id', '`pegawai_id`', '`pegawai_id`', 3, -1, FALSE, '`pegawai_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pegawai_id->Sortable = TRUE; // Allow sort
		$this->pegawai_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['pegawai_id'] = &$this->pegawai_id;

		// jatah_c_mulai
		$this->jatah_c_mulai = new cField('jatah_cuti', 'jatah_cuti', 'x_jatah_c_mulai', 'jatah_c_mulai', '`jatah_c_mulai`', ew_CastDateFieldForLike('`jatah_c_mulai`', 0, "DB"), 133, 0, FALSE, '`jatah_c_mulai`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jatah_c_mulai->Sortable = TRUE; // Allow sort
		$this->jatah_c_mulai->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['jatah_c_mulai'] = &$this->jatah_c_mulai;

		// jatah_c_akhir
		$this->jatah_c_akhir = new cField('jatah_cuti', 'jatah_cuti', 'x_jatah_c_akhir', 'jatah_c_akhir', '`jatah_c_akhir`', ew_CastDateFieldForLike('`jatah_c_akhir`', 0, "DB"), 133, 0, FALSE, '`jatah_c_akhir`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jatah_c_akhir->Sortable = TRUE; // Allow sort
		$this->jatah_c_akhir->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['jatah_c_akhir'] = &$this->jatah_c_akhir;

		// jatah_c_jml
		$this->jatah_c_jml = new cField('jatah_cuti', 'jatah_cuti', 'x_jatah_c_jml', 'jatah_c_jml', '`jatah_c_jml`', '`jatah_c_jml`', 2, -1, FALSE, '`jatah_c_jml`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jatah_c_jml->Sortable = TRUE; // Allow sort
		$this->jatah_c_jml->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['jatah_c_jml'] = &$this->jatah_c_jml;

		// jatah_c_hak_jml
		$this->jatah_c_hak_jml = new cField('jatah_cuti', 'jatah_cuti', 'x_jatah_c_hak_jml', 'jatah_c_hak_jml', '`jatah_c_hak_jml`', '`jatah_c_hak_jml`', 2, -1, FALSE, '`jatah_c_hak_jml`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jatah_c_hak_jml->Sortable = TRUE; // Allow sort
		$this->jatah_c_hak_jml->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['jatah_c_hak_jml'] = &$this->jatah_c_hak_jml;

		// jatah_c_ambil_jml
		$this->jatah_c_ambil_jml = new cField('jatah_cuti', 'jatah_cuti', 'x_jatah_c_ambil_jml', 'jatah_c_ambil_jml', '`jatah_c_ambil_jml`', '`jatah_c_ambil_jml`', 2, -1, FALSE, '`jatah_c_ambil_jml`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jatah_c_ambil_jml->Sortable = TRUE; // Allow sort
		$this->jatah_c_ambil_jml->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['jatah_c_ambil_jml'] = &$this->jatah_c_ambil_jml;

		// jatah_c_utang_jml
		$this->jatah_c_utang_jml = new cField('jatah_cuti', 'jatah_cuti', 'x_jatah_c_utang_jml', 'jatah_c_utang_jml', '`jatah_c_utang_jml`', '`jatah_c_utang_jml`', 2, -1, FALSE, '`jatah_c_utang_jml`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jatah_c_utang_jml->Sortable = TRUE; // Allow sort
		$this->jatah_c_utang_jml->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['jatah_c_utang_jml'] = &$this->jatah_c_utang_jml;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`jatah_cuti`";
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
			if (array_key_exists('jatah_c_mulai', $rs))
				ew_AddFilter($where, ew_QuotedName('jatah_c_mulai', $this->DBID) . '=' . ew_QuotedValue($rs['jatah_c_mulai'], $this->jatah_c_mulai->FldDataType, $this->DBID));
			if (array_key_exists('jatah_c_akhir', $rs))
				ew_AddFilter($where, ew_QuotedName('jatah_c_akhir', $this->DBID) . '=' . ew_QuotedValue($rs['jatah_c_akhir'], $this->jatah_c_akhir->FldDataType, $this->DBID));
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
		return "`pegawai_id` = @pegawai_id@ AND `jatah_c_mulai` = '@jatah_c_mulai@' AND `jatah_c_akhir` = '@jatah_c_akhir@'";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->pegawai_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@pegawai_id@", ew_AdjustSql($this->pegawai_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		$sKeyFilter = str_replace("@jatah_c_mulai@", ew_AdjustSql(ew_UnFormatDateTime($this->jatah_c_mulai->CurrentValue,0), $this->DBID), $sKeyFilter); // Replace key value
		$sKeyFilter = str_replace("@jatah_c_akhir@", ew_AdjustSql(ew_UnFormatDateTime($this->jatah_c_akhir->CurrentValue,0), $this->DBID), $sKeyFilter); // Replace key value
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
			return "jatah_cutilist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "jatah_cutilist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("jatah_cutiview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("jatah_cutiview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "jatah_cutiadd.php?" . $this->UrlParm($parm);
		else
			$url = "jatah_cutiadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("jatah_cutiedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("jatah_cutiadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("jatah_cutidelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "pegawai_id:" . ew_VarToJson($this->pegawai_id->CurrentValue, "number", "'");
		$json .= ",jatah_c_mulai:" . ew_VarToJson($this->jatah_c_mulai->CurrentValue, "string", "'");
		$json .= ",jatah_c_akhir:" . ew_VarToJson($this->jatah_c_akhir->CurrentValue, "string", "'");
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
		if (!is_null($this->jatah_c_mulai->CurrentValue)) {
			$sUrl .= "&jatah_c_mulai=" . urlencode($this->jatah_c_mulai->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->jatah_c_akhir->CurrentValue)) {
			$sUrl .= "&jatah_c_akhir=" . urlencode($this->jatah_c_akhir->CurrentValue);
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
			if ($isPost && isset($_POST["jatah_c_mulai"]))
				$arKey[] = ew_StripSlashes($_POST["jatah_c_mulai"]);
			elseif (isset($_GET["jatah_c_mulai"]))
				$arKey[] = ew_StripSlashes($_GET["jatah_c_mulai"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["jatah_c_akhir"]))
				$arKey[] = ew_StripSlashes($_POST["jatah_c_akhir"]);
			elseif (isset($_GET["jatah_c_akhir"]))
				$arKey[] = ew_StripSlashes($_GET["jatah_c_akhir"]);
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
			$this->jatah_c_mulai->CurrentValue = $key[1];
			$this->jatah_c_akhir->CurrentValue = $key[2];
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
		$this->jatah_c_mulai->setDbValue($rs->fields('jatah_c_mulai'));
		$this->jatah_c_akhir->setDbValue($rs->fields('jatah_c_akhir'));
		$this->jatah_c_jml->setDbValue($rs->fields('jatah_c_jml'));
		$this->jatah_c_hak_jml->setDbValue($rs->fields('jatah_c_hak_jml'));
		$this->jatah_c_ambil_jml->setDbValue($rs->fields('jatah_c_ambil_jml'));
		$this->jatah_c_utang_jml->setDbValue($rs->fields('jatah_c_utang_jml'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// pegawai_id
		// jatah_c_mulai
		// jatah_c_akhir
		// jatah_c_jml
		// jatah_c_hak_jml
		// jatah_c_ambil_jml
		// jatah_c_utang_jml
		// pegawai_id

		$this->pegawai_id->ViewValue = $this->pegawai_id->CurrentValue;
		$this->pegawai_id->ViewCustomAttributes = "";

		// jatah_c_mulai
		$this->jatah_c_mulai->ViewValue = $this->jatah_c_mulai->CurrentValue;
		$this->jatah_c_mulai->ViewValue = ew_FormatDateTime($this->jatah_c_mulai->ViewValue, 0);
		$this->jatah_c_mulai->ViewCustomAttributes = "";

		// jatah_c_akhir
		$this->jatah_c_akhir->ViewValue = $this->jatah_c_akhir->CurrentValue;
		$this->jatah_c_akhir->ViewValue = ew_FormatDateTime($this->jatah_c_akhir->ViewValue, 0);
		$this->jatah_c_akhir->ViewCustomAttributes = "";

		// jatah_c_jml
		$this->jatah_c_jml->ViewValue = $this->jatah_c_jml->CurrentValue;
		$this->jatah_c_jml->ViewCustomAttributes = "";

		// jatah_c_hak_jml
		$this->jatah_c_hak_jml->ViewValue = $this->jatah_c_hak_jml->CurrentValue;
		$this->jatah_c_hak_jml->ViewCustomAttributes = "";

		// jatah_c_ambil_jml
		$this->jatah_c_ambil_jml->ViewValue = $this->jatah_c_ambil_jml->CurrentValue;
		$this->jatah_c_ambil_jml->ViewCustomAttributes = "";

		// jatah_c_utang_jml
		$this->jatah_c_utang_jml->ViewValue = $this->jatah_c_utang_jml->CurrentValue;
		$this->jatah_c_utang_jml->ViewCustomAttributes = "";

		// pegawai_id
		$this->pegawai_id->LinkCustomAttributes = "";
		$this->pegawai_id->HrefValue = "";
		$this->pegawai_id->TooltipValue = "";

		// jatah_c_mulai
		$this->jatah_c_mulai->LinkCustomAttributes = "";
		$this->jatah_c_mulai->HrefValue = "";
		$this->jatah_c_mulai->TooltipValue = "";

		// jatah_c_akhir
		$this->jatah_c_akhir->LinkCustomAttributes = "";
		$this->jatah_c_akhir->HrefValue = "";
		$this->jatah_c_akhir->TooltipValue = "";

		// jatah_c_jml
		$this->jatah_c_jml->LinkCustomAttributes = "";
		$this->jatah_c_jml->HrefValue = "";
		$this->jatah_c_jml->TooltipValue = "";

		// jatah_c_hak_jml
		$this->jatah_c_hak_jml->LinkCustomAttributes = "";
		$this->jatah_c_hak_jml->HrefValue = "";
		$this->jatah_c_hak_jml->TooltipValue = "";

		// jatah_c_ambil_jml
		$this->jatah_c_ambil_jml->LinkCustomAttributes = "";
		$this->jatah_c_ambil_jml->HrefValue = "";
		$this->jatah_c_ambil_jml->TooltipValue = "";

		// jatah_c_utang_jml
		$this->jatah_c_utang_jml->LinkCustomAttributes = "";
		$this->jatah_c_utang_jml->HrefValue = "";
		$this->jatah_c_utang_jml->TooltipValue = "";

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

		// jatah_c_mulai
		$this->jatah_c_mulai->EditAttrs["class"] = "form-control";
		$this->jatah_c_mulai->EditCustomAttributes = "";
		$this->jatah_c_mulai->EditValue = $this->jatah_c_mulai->CurrentValue;
		$this->jatah_c_mulai->EditValue = ew_FormatDateTime($this->jatah_c_mulai->EditValue, 0);
		$this->jatah_c_mulai->ViewCustomAttributes = "";

		// jatah_c_akhir
		$this->jatah_c_akhir->EditAttrs["class"] = "form-control";
		$this->jatah_c_akhir->EditCustomAttributes = "";
		$this->jatah_c_akhir->EditValue = $this->jatah_c_akhir->CurrentValue;
		$this->jatah_c_akhir->EditValue = ew_FormatDateTime($this->jatah_c_akhir->EditValue, 0);
		$this->jatah_c_akhir->ViewCustomAttributes = "";

		// jatah_c_jml
		$this->jatah_c_jml->EditAttrs["class"] = "form-control";
		$this->jatah_c_jml->EditCustomAttributes = "";
		$this->jatah_c_jml->EditValue = $this->jatah_c_jml->CurrentValue;
		$this->jatah_c_jml->PlaceHolder = ew_RemoveHtml($this->jatah_c_jml->FldCaption());

		// jatah_c_hak_jml
		$this->jatah_c_hak_jml->EditAttrs["class"] = "form-control";
		$this->jatah_c_hak_jml->EditCustomAttributes = "";
		$this->jatah_c_hak_jml->EditValue = $this->jatah_c_hak_jml->CurrentValue;
		$this->jatah_c_hak_jml->PlaceHolder = ew_RemoveHtml($this->jatah_c_hak_jml->FldCaption());

		// jatah_c_ambil_jml
		$this->jatah_c_ambil_jml->EditAttrs["class"] = "form-control";
		$this->jatah_c_ambil_jml->EditCustomAttributes = "";
		$this->jatah_c_ambil_jml->EditValue = $this->jatah_c_ambil_jml->CurrentValue;
		$this->jatah_c_ambil_jml->PlaceHolder = ew_RemoveHtml($this->jatah_c_ambil_jml->FldCaption());

		// jatah_c_utang_jml
		$this->jatah_c_utang_jml->EditAttrs["class"] = "form-control";
		$this->jatah_c_utang_jml->EditCustomAttributes = "";
		$this->jatah_c_utang_jml->EditValue = $this->jatah_c_utang_jml->CurrentValue;
		$this->jatah_c_utang_jml->PlaceHolder = ew_RemoveHtml($this->jatah_c_utang_jml->FldCaption());

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
					if ($this->jatah_c_mulai->Exportable) $Doc->ExportCaption($this->jatah_c_mulai);
					if ($this->jatah_c_akhir->Exportable) $Doc->ExportCaption($this->jatah_c_akhir);
					if ($this->jatah_c_jml->Exportable) $Doc->ExportCaption($this->jatah_c_jml);
					if ($this->jatah_c_hak_jml->Exportable) $Doc->ExportCaption($this->jatah_c_hak_jml);
					if ($this->jatah_c_ambil_jml->Exportable) $Doc->ExportCaption($this->jatah_c_ambil_jml);
					if ($this->jatah_c_utang_jml->Exportable) $Doc->ExportCaption($this->jatah_c_utang_jml);
				} else {
					if ($this->pegawai_id->Exportable) $Doc->ExportCaption($this->pegawai_id);
					if ($this->jatah_c_mulai->Exportable) $Doc->ExportCaption($this->jatah_c_mulai);
					if ($this->jatah_c_akhir->Exportable) $Doc->ExportCaption($this->jatah_c_akhir);
					if ($this->jatah_c_jml->Exportable) $Doc->ExportCaption($this->jatah_c_jml);
					if ($this->jatah_c_hak_jml->Exportable) $Doc->ExportCaption($this->jatah_c_hak_jml);
					if ($this->jatah_c_ambil_jml->Exportable) $Doc->ExportCaption($this->jatah_c_ambil_jml);
					if ($this->jatah_c_utang_jml->Exportable) $Doc->ExportCaption($this->jatah_c_utang_jml);
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
						if ($this->jatah_c_mulai->Exportable) $Doc->ExportField($this->jatah_c_mulai);
						if ($this->jatah_c_akhir->Exportable) $Doc->ExportField($this->jatah_c_akhir);
						if ($this->jatah_c_jml->Exportable) $Doc->ExportField($this->jatah_c_jml);
						if ($this->jatah_c_hak_jml->Exportable) $Doc->ExportField($this->jatah_c_hak_jml);
						if ($this->jatah_c_ambil_jml->Exportable) $Doc->ExportField($this->jatah_c_ambil_jml);
						if ($this->jatah_c_utang_jml->Exportable) $Doc->ExportField($this->jatah_c_utang_jml);
					} else {
						if ($this->pegawai_id->Exportable) $Doc->ExportField($this->pegawai_id);
						if ($this->jatah_c_mulai->Exportable) $Doc->ExportField($this->jatah_c_mulai);
						if ($this->jatah_c_akhir->Exportable) $Doc->ExportField($this->jatah_c_akhir);
						if ($this->jatah_c_jml->Exportable) $Doc->ExportField($this->jatah_c_jml);
						if ($this->jatah_c_hak_jml->Exportable) $Doc->ExportField($this->jatah_c_hak_jml);
						if ($this->jatah_c_ambil_jml->Exportable) $Doc->ExportField($this->jatah_c_ambil_jml);
						if ($this->jatah_c_utang_jml->Exportable) $Doc->ExportField($this->jatah_c_utang_jml);
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
