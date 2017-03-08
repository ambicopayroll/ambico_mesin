<?php

// Global variable for table object
$zx_bayar_kredit = NULL;

//
// Table class for zx_bayar_kredit
//
class czx_bayar_kredit extends cTable {
	var $id_bayar;
	var $tgl_bayar;
	var $id_kredit;
	var $no_urut;
	var $tgl_jt;
	var $debet;
	var $angs_pokok;
	var $bunga;
	var $emp_id_auto;
	var $keterangan;
	var $status;
	var $lastupdate_date;
	var $lastupdate_user;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'zx_bayar_kredit';
		$this->TableName = 'zx_bayar_kredit';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`zx_bayar_kredit`";
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

		// id_bayar
		$this->id_bayar = new cField('zx_bayar_kredit', 'zx_bayar_kredit', 'x_id_bayar', 'id_bayar', '`id_bayar`', '`id_bayar`', 3, -1, FALSE, '`id_bayar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->id_bayar->Sortable = TRUE; // Allow sort
		$this->id_bayar->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_bayar'] = &$this->id_bayar;

		// tgl_bayar
		$this->tgl_bayar = new cField('zx_bayar_kredit', 'zx_bayar_kredit', 'x_tgl_bayar', 'tgl_bayar', '`tgl_bayar`', ew_CastDateFieldForLike('`tgl_bayar`', 0, "DB"), 133, 0, FALSE, '`tgl_bayar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tgl_bayar->Sortable = TRUE; // Allow sort
		$this->tgl_bayar->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['tgl_bayar'] = &$this->tgl_bayar;

		// id_kredit
		$this->id_kredit = new cField('zx_bayar_kredit', 'zx_bayar_kredit', 'x_id_kredit', 'id_kredit', '`id_kredit`', '`id_kredit`', 3, -1, FALSE, '`id_kredit`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->id_kredit->Sortable = TRUE; // Allow sort
		$this->id_kredit->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_kredit'] = &$this->id_kredit;

		// no_urut
		$this->no_urut = new cField('zx_bayar_kredit', 'zx_bayar_kredit', 'x_no_urut', 'no_urut', '`no_urut`', '`no_urut`', 2, -1, FALSE, '`no_urut`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->no_urut->Sortable = TRUE; // Allow sort
		$this->no_urut->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['no_urut'] = &$this->no_urut;

		// tgl_jt
		$this->tgl_jt = new cField('zx_bayar_kredit', 'zx_bayar_kredit', 'x_tgl_jt', 'tgl_jt', '`tgl_jt`', ew_CastDateFieldForLike('`tgl_jt`', 0, "DB"), 133, 0, FALSE, '`tgl_jt`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tgl_jt->Sortable = TRUE; // Allow sort
		$this->tgl_jt->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['tgl_jt'] = &$this->tgl_jt;

		// debet
		$this->debet = new cField('zx_bayar_kredit', 'zx_bayar_kredit', 'x_debet', 'debet', '`debet`', '`debet`', 4, -1, FALSE, '`debet`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->debet->Sortable = TRUE; // Allow sort
		$this->debet->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['debet'] = &$this->debet;

		// angs_pokok
		$this->angs_pokok = new cField('zx_bayar_kredit', 'zx_bayar_kredit', 'x_angs_pokok', 'angs_pokok', '`angs_pokok`', '`angs_pokok`', 4, -1, FALSE, '`angs_pokok`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->angs_pokok->Sortable = TRUE; // Allow sort
		$this->angs_pokok->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['angs_pokok'] = &$this->angs_pokok;

		// bunga
		$this->bunga = new cField('zx_bayar_kredit', 'zx_bayar_kredit', 'x_bunga', 'bunga', '`bunga`', '`bunga`', 4, -1, FALSE, '`bunga`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->bunga->Sortable = TRUE; // Allow sort
		$this->bunga->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['bunga'] = &$this->bunga;

		// emp_id_auto
		$this->emp_id_auto = new cField('zx_bayar_kredit', 'zx_bayar_kredit', 'x_emp_id_auto', 'emp_id_auto', '`emp_id_auto`', '`emp_id_auto`', 3, -1, FALSE, '`emp_id_auto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->emp_id_auto->Sortable = TRUE; // Allow sort
		$this->emp_id_auto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['emp_id_auto'] = &$this->emp_id_auto;

		// keterangan
		$this->keterangan = new cField('zx_bayar_kredit', 'zx_bayar_kredit', 'x_keterangan', 'keterangan', '`keterangan`', '`keterangan`', 201, -1, FALSE, '`keterangan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->keterangan->Sortable = TRUE; // Allow sort
		$this->fields['keterangan'] = &$this->keterangan;

		// status
		$this->status = new cField('zx_bayar_kredit', 'zx_bayar_kredit', 'x_status', 'status', '`status`', '`status`', 16, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->status->Sortable = TRUE; // Allow sort
		$this->status->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['status'] = &$this->status;

		// lastupdate_date
		$this->lastupdate_date = new cField('zx_bayar_kredit', 'zx_bayar_kredit', 'x_lastupdate_date', 'lastupdate_date', '`lastupdate_date`', ew_CastDateFieldForLike('`lastupdate_date`', 0, "DB"), 135, 0, FALSE, '`lastupdate_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->lastupdate_date->Sortable = TRUE; // Allow sort
		$this->lastupdate_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['lastupdate_date'] = &$this->lastupdate_date;

		// lastupdate_user
		$this->lastupdate_user = new cField('zx_bayar_kredit', 'zx_bayar_kredit', 'x_lastupdate_user', 'lastupdate_user', '`lastupdate_user`', '`lastupdate_user`', 200, -1, FALSE, '`lastupdate_user`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`zx_bayar_kredit`";
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
			if (array_key_exists('id_bayar', $rs))
				ew_AddFilter($where, ew_QuotedName('id_bayar', $this->DBID) . '=' . ew_QuotedValue($rs['id_bayar'], $this->id_bayar->FldDataType, $this->DBID));
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
		return "`id_bayar` = @id_bayar@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id_bayar->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id_bayar@", ew_AdjustSql($this->id_bayar->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "zx_bayar_kreditlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "zx_bayar_kreditlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("zx_bayar_kreditview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("zx_bayar_kreditview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "zx_bayar_kreditadd.php?" . $this->UrlParm($parm);
		else
			$url = "zx_bayar_kreditadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("zx_bayar_kreditedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("zx_bayar_kreditadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("zx_bayar_kreditdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "id_bayar:" . ew_VarToJson($this->id_bayar->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id_bayar->CurrentValue)) {
			$sUrl .= "id_bayar=" . urlencode($this->id_bayar->CurrentValue);
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
			if ($isPost && isset($_POST["id_bayar"]))
				$arKeys[] = ew_StripSlashes($_POST["id_bayar"]);
			elseif (isset($_GET["id_bayar"]))
				$arKeys[] = ew_StripSlashes($_GET["id_bayar"]);
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
			$this->id_bayar->CurrentValue = $key;
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
		$this->id_bayar->setDbValue($rs->fields('id_bayar'));
		$this->tgl_bayar->setDbValue($rs->fields('tgl_bayar'));
		$this->id_kredit->setDbValue($rs->fields('id_kredit'));
		$this->no_urut->setDbValue($rs->fields('no_urut'));
		$this->tgl_jt->setDbValue($rs->fields('tgl_jt'));
		$this->debet->setDbValue($rs->fields('debet'));
		$this->angs_pokok->setDbValue($rs->fields('angs_pokok'));
		$this->bunga->setDbValue($rs->fields('bunga'));
		$this->emp_id_auto->setDbValue($rs->fields('emp_id_auto'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
		$this->status->setDbValue($rs->fields('status'));
		$this->lastupdate_date->setDbValue($rs->fields('lastupdate_date'));
		$this->lastupdate_user->setDbValue($rs->fields('lastupdate_user'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id_bayar
		// tgl_bayar
		// id_kredit
		// no_urut
		// tgl_jt
		// debet
		// angs_pokok
		// bunga
		// emp_id_auto
		// keterangan
		// status
		// lastupdate_date
		// lastupdate_user
		// id_bayar

		$this->id_bayar->ViewValue = $this->id_bayar->CurrentValue;
		$this->id_bayar->ViewCustomAttributes = "";

		// tgl_bayar
		$this->tgl_bayar->ViewValue = $this->tgl_bayar->CurrentValue;
		$this->tgl_bayar->ViewValue = ew_FormatDateTime($this->tgl_bayar->ViewValue, 0);
		$this->tgl_bayar->ViewCustomAttributes = "";

		// id_kredit
		$this->id_kredit->ViewValue = $this->id_kredit->CurrentValue;
		$this->id_kredit->ViewCustomAttributes = "";

		// no_urut
		$this->no_urut->ViewValue = $this->no_urut->CurrentValue;
		$this->no_urut->ViewCustomAttributes = "";

		// tgl_jt
		$this->tgl_jt->ViewValue = $this->tgl_jt->CurrentValue;
		$this->tgl_jt->ViewValue = ew_FormatDateTime($this->tgl_jt->ViewValue, 0);
		$this->tgl_jt->ViewCustomAttributes = "";

		// debet
		$this->debet->ViewValue = $this->debet->CurrentValue;
		$this->debet->ViewCustomAttributes = "";

		// angs_pokok
		$this->angs_pokok->ViewValue = $this->angs_pokok->CurrentValue;
		$this->angs_pokok->ViewCustomAttributes = "";

		// bunga
		$this->bunga->ViewValue = $this->bunga->CurrentValue;
		$this->bunga->ViewCustomAttributes = "";

		// emp_id_auto
		$this->emp_id_auto->ViewValue = $this->emp_id_auto->CurrentValue;
		$this->emp_id_auto->ViewCustomAttributes = "";

		// keterangan
		$this->keterangan->ViewValue = $this->keterangan->CurrentValue;
		$this->keterangan->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		$this->status->ViewCustomAttributes = "";

		// lastupdate_date
		$this->lastupdate_date->ViewValue = $this->lastupdate_date->CurrentValue;
		$this->lastupdate_date->ViewValue = ew_FormatDateTime($this->lastupdate_date->ViewValue, 0);
		$this->lastupdate_date->ViewCustomAttributes = "";

		// lastupdate_user
		$this->lastupdate_user->ViewValue = $this->lastupdate_user->CurrentValue;
		$this->lastupdate_user->ViewCustomAttributes = "";

		// id_bayar
		$this->id_bayar->LinkCustomAttributes = "";
		$this->id_bayar->HrefValue = "";
		$this->id_bayar->TooltipValue = "";

		// tgl_bayar
		$this->tgl_bayar->LinkCustomAttributes = "";
		$this->tgl_bayar->HrefValue = "";
		$this->tgl_bayar->TooltipValue = "";

		// id_kredit
		$this->id_kredit->LinkCustomAttributes = "";
		$this->id_kredit->HrefValue = "";
		$this->id_kredit->TooltipValue = "";

		// no_urut
		$this->no_urut->LinkCustomAttributes = "";
		$this->no_urut->HrefValue = "";
		$this->no_urut->TooltipValue = "";

		// tgl_jt
		$this->tgl_jt->LinkCustomAttributes = "";
		$this->tgl_jt->HrefValue = "";
		$this->tgl_jt->TooltipValue = "";

		// debet
		$this->debet->LinkCustomAttributes = "";
		$this->debet->HrefValue = "";
		$this->debet->TooltipValue = "";

		// angs_pokok
		$this->angs_pokok->LinkCustomAttributes = "";
		$this->angs_pokok->HrefValue = "";
		$this->angs_pokok->TooltipValue = "";

		// bunga
		$this->bunga->LinkCustomAttributes = "";
		$this->bunga->HrefValue = "";
		$this->bunga->TooltipValue = "";

		// emp_id_auto
		$this->emp_id_auto->LinkCustomAttributes = "";
		$this->emp_id_auto->HrefValue = "";
		$this->emp_id_auto->TooltipValue = "";

		// keterangan
		$this->keterangan->LinkCustomAttributes = "";
		$this->keterangan->HrefValue = "";
		$this->keterangan->TooltipValue = "";

		// status
		$this->status->LinkCustomAttributes = "";
		$this->status->HrefValue = "";
		$this->status->TooltipValue = "";

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

		// id_bayar
		$this->id_bayar->EditAttrs["class"] = "form-control";
		$this->id_bayar->EditCustomAttributes = "";
		$this->id_bayar->EditValue = $this->id_bayar->CurrentValue;
		$this->id_bayar->ViewCustomAttributes = "";

		// tgl_bayar
		$this->tgl_bayar->EditAttrs["class"] = "form-control";
		$this->tgl_bayar->EditCustomAttributes = "";
		$this->tgl_bayar->EditValue = ew_FormatDateTime($this->tgl_bayar->CurrentValue, 8);
		$this->tgl_bayar->PlaceHolder = ew_RemoveHtml($this->tgl_bayar->FldCaption());

		// id_kredit
		$this->id_kredit->EditAttrs["class"] = "form-control";
		$this->id_kredit->EditCustomAttributes = "";
		$this->id_kredit->EditValue = $this->id_kredit->CurrentValue;
		$this->id_kredit->PlaceHolder = ew_RemoveHtml($this->id_kredit->FldCaption());

		// no_urut
		$this->no_urut->EditAttrs["class"] = "form-control";
		$this->no_urut->EditCustomAttributes = "";
		$this->no_urut->EditValue = $this->no_urut->CurrentValue;
		$this->no_urut->PlaceHolder = ew_RemoveHtml($this->no_urut->FldCaption());

		// tgl_jt
		$this->tgl_jt->EditAttrs["class"] = "form-control";
		$this->tgl_jt->EditCustomAttributes = "";
		$this->tgl_jt->EditValue = ew_FormatDateTime($this->tgl_jt->CurrentValue, 8);
		$this->tgl_jt->PlaceHolder = ew_RemoveHtml($this->tgl_jt->FldCaption());

		// debet
		$this->debet->EditAttrs["class"] = "form-control";
		$this->debet->EditCustomAttributes = "";
		$this->debet->EditValue = $this->debet->CurrentValue;
		$this->debet->PlaceHolder = ew_RemoveHtml($this->debet->FldCaption());
		if (strval($this->debet->EditValue) <> "" && is_numeric($this->debet->EditValue)) $this->debet->EditValue = ew_FormatNumber($this->debet->EditValue, -2, -1, -2, 0);

		// angs_pokok
		$this->angs_pokok->EditAttrs["class"] = "form-control";
		$this->angs_pokok->EditCustomAttributes = "";
		$this->angs_pokok->EditValue = $this->angs_pokok->CurrentValue;
		$this->angs_pokok->PlaceHolder = ew_RemoveHtml($this->angs_pokok->FldCaption());
		if (strval($this->angs_pokok->EditValue) <> "" && is_numeric($this->angs_pokok->EditValue)) $this->angs_pokok->EditValue = ew_FormatNumber($this->angs_pokok->EditValue, -2, -1, -2, 0);

		// bunga
		$this->bunga->EditAttrs["class"] = "form-control";
		$this->bunga->EditCustomAttributes = "";
		$this->bunga->EditValue = $this->bunga->CurrentValue;
		$this->bunga->PlaceHolder = ew_RemoveHtml($this->bunga->FldCaption());
		if (strval($this->bunga->EditValue) <> "" && is_numeric($this->bunga->EditValue)) $this->bunga->EditValue = ew_FormatNumber($this->bunga->EditValue, -2, -1, -2, 0);

		// emp_id_auto
		$this->emp_id_auto->EditAttrs["class"] = "form-control";
		$this->emp_id_auto->EditCustomAttributes = "";
		$this->emp_id_auto->EditValue = $this->emp_id_auto->CurrentValue;
		$this->emp_id_auto->PlaceHolder = ew_RemoveHtml($this->emp_id_auto->FldCaption());

		// keterangan
		$this->keterangan->EditAttrs["class"] = "form-control";
		$this->keterangan->EditCustomAttributes = "";
		$this->keterangan->EditValue = $this->keterangan->CurrentValue;
		$this->keterangan->PlaceHolder = ew_RemoveHtml($this->keterangan->FldCaption());

		// status
		$this->status->EditAttrs["class"] = "form-control";
		$this->status->EditCustomAttributes = "";
		$this->status->EditValue = $this->status->CurrentValue;
		$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

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
					if ($this->id_bayar->Exportable) $Doc->ExportCaption($this->id_bayar);
					if ($this->tgl_bayar->Exportable) $Doc->ExportCaption($this->tgl_bayar);
					if ($this->id_kredit->Exportable) $Doc->ExportCaption($this->id_kredit);
					if ($this->no_urut->Exportable) $Doc->ExportCaption($this->no_urut);
					if ($this->tgl_jt->Exportable) $Doc->ExportCaption($this->tgl_jt);
					if ($this->debet->Exportable) $Doc->ExportCaption($this->debet);
					if ($this->angs_pokok->Exportable) $Doc->ExportCaption($this->angs_pokok);
					if ($this->bunga->Exportable) $Doc->ExportCaption($this->bunga);
					if ($this->emp_id_auto->Exportable) $Doc->ExportCaption($this->emp_id_auto);
					if ($this->keterangan->Exportable) $Doc->ExportCaption($this->keterangan);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->lastupdate_date->Exportable) $Doc->ExportCaption($this->lastupdate_date);
					if ($this->lastupdate_user->Exportable) $Doc->ExportCaption($this->lastupdate_user);
				} else {
					if ($this->id_bayar->Exportable) $Doc->ExportCaption($this->id_bayar);
					if ($this->tgl_bayar->Exportable) $Doc->ExportCaption($this->tgl_bayar);
					if ($this->id_kredit->Exportable) $Doc->ExportCaption($this->id_kredit);
					if ($this->no_urut->Exportable) $Doc->ExportCaption($this->no_urut);
					if ($this->tgl_jt->Exportable) $Doc->ExportCaption($this->tgl_jt);
					if ($this->debet->Exportable) $Doc->ExportCaption($this->debet);
					if ($this->angs_pokok->Exportable) $Doc->ExportCaption($this->angs_pokok);
					if ($this->bunga->Exportable) $Doc->ExportCaption($this->bunga);
					if ($this->emp_id_auto->Exportable) $Doc->ExportCaption($this->emp_id_auto);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
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
						if ($this->id_bayar->Exportable) $Doc->ExportField($this->id_bayar);
						if ($this->tgl_bayar->Exportable) $Doc->ExportField($this->tgl_bayar);
						if ($this->id_kredit->Exportable) $Doc->ExportField($this->id_kredit);
						if ($this->no_urut->Exportable) $Doc->ExportField($this->no_urut);
						if ($this->tgl_jt->Exportable) $Doc->ExportField($this->tgl_jt);
						if ($this->debet->Exportable) $Doc->ExportField($this->debet);
						if ($this->angs_pokok->Exportable) $Doc->ExportField($this->angs_pokok);
						if ($this->bunga->Exportable) $Doc->ExportField($this->bunga);
						if ($this->emp_id_auto->Exportable) $Doc->ExportField($this->emp_id_auto);
						if ($this->keterangan->Exportable) $Doc->ExportField($this->keterangan);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->lastupdate_date->Exportable) $Doc->ExportField($this->lastupdate_date);
						if ($this->lastupdate_user->Exportable) $Doc->ExportField($this->lastupdate_user);
					} else {
						if ($this->id_bayar->Exportable) $Doc->ExportField($this->id_bayar);
						if ($this->tgl_bayar->Exportable) $Doc->ExportField($this->tgl_bayar);
						if ($this->id_kredit->Exportable) $Doc->ExportField($this->id_kredit);
						if ($this->no_urut->Exportable) $Doc->ExportField($this->no_urut);
						if ($this->tgl_jt->Exportable) $Doc->ExportField($this->tgl_jt);
						if ($this->debet->Exportable) $Doc->ExportField($this->debet);
						if ($this->angs_pokok->Exportable) $Doc->ExportField($this->angs_pokok);
						if ($this->bunga->Exportable) $Doc->ExportField($this->bunga);
						if ($this->emp_id_auto->Exportable) $Doc->ExportField($this->emp_id_auto);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
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
