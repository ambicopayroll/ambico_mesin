<?php

// Global variable for table object
$z_pay_grp_com = NULL;

//
// Table class for z_pay_grp_com
//
class cz_pay_grp_com extends cTable {
	var $grp_id;
	var $com_id;
	var $no_urut_ref;
	var $use_if_sum;
	var $use_kode_if;
	var $id_kode_if;
	var $min_if;
	var $max_if;
	var $use_sum;
	var $use_kode_sum;
	var $id_kode_sum;
	var $operator_sum;
	var $konstanta_sum;
	var $operator_sum2;
	var $nilai_rp;
	var $hitung;
	var $jenis;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'z_pay_grp_com';
		$this->TableName = 'z_pay_grp_com';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`z_pay_grp_com`";
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

		// grp_id
		$this->grp_id = new cField('z_pay_grp_com', 'z_pay_grp_com', 'x_grp_id', 'grp_id', '`grp_id`', '`grp_id`', 2, -1, FALSE, '`grp_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->grp_id->Sortable = TRUE; // Allow sort
		$this->grp_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['grp_id'] = &$this->grp_id;

		// com_id
		$this->com_id = new cField('z_pay_grp_com', 'z_pay_grp_com', 'x_com_id', 'com_id', '`com_id`', '`com_id`', 2, -1, FALSE, '`com_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->com_id->Sortable = TRUE; // Allow sort
		$this->com_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['com_id'] = &$this->com_id;

		// no_urut_ref
		$this->no_urut_ref = new cField('z_pay_grp_com', 'z_pay_grp_com', 'x_no_urut_ref', 'no_urut_ref', '`no_urut_ref`', '`no_urut_ref`', 2, -1, FALSE, '`no_urut_ref`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->no_urut_ref->Sortable = TRUE; // Allow sort
		$this->no_urut_ref->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['no_urut_ref'] = &$this->no_urut_ref;

		// use_if_sum
		$this->use_if_sum = new cField('z_pay_grp_com', 'z_pay_grp_com', 'x_use_if_sum', 'use_if_sum', '`use_if_sum`', '`use_if_sum`', 16, -1, FALSE, '`use_if_sum`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->use_if_sum->Sortable = TRUE; // Allow sort
		$this->use_if_sum->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['use_if_sum'] = &$this->use_if_sum;

		// use_kode_if
		$this->use_kode_if = new cField('z_pay_grp_com', 'z_pay_grp_com', 'x_use_kode_if', 'use_kode_if', '`use_kode_if`', '`use_kode_if`', 16, -1, FALSE, '`use_kode_if`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->use_kode_if->Sortable = TRUE; // Allow sort
		$this->use_kode_if->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['use_kode_if'] = &$this->use_kode_if;

		// id_kode_if
		$this->id_kode_if = new cField('z_pay_grp_com', 'z_pay_grp_com', 'x_id_kode_if', 'id_kode_if', '`id_kode_if`', '`id_kode_if`', 2, -1, FALSE, '`id_kode_if`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->id_kode_if->Sortable = TRUE; // Allow sort
		$this->id_kode_if->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_kode_if'] = &$this->id_kode_if;

		// min_if
		$this->min_if = new cField('z_pay_grp_com', 'z_pay_grp_com', 'x_min_if', 'min_if', '`min_if`', '`min_if`', 4, -1, FALSE, '`min_if`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->min_if->Sortable = TRUE; // Allow sort
		$this->min_if->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['min_if'] = &$this->min_if;

		// max_if
		$this->max_if = new cField('z_pay_grp_com', 'z_pay_grp_com', 'x_max_if', 'max_if', '`max_if`', '`max_if`', 4, -1, FALSE, '`max_if`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->max_if->Sortable = TRUE; // Allow sort
		$this->max_if->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['max_if'] = &$this->max_if;

		// use_sum
		$this->use_sum = new cField('z_pay_grp_com', 'z_pay_grp_com', 'x_use_sum', 'use_sum', '`use_sum`', '`use_sum`', 16, -1, FALSE, '`use_sum`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->use_sum->Sortable = TRUE; // Allow sort
		$this->use_sum->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['use_sum'] = &$this->use_sum;

		// use_kode_sum
		$this->use_kode_sum = new cField('z_pay_grp_com', 'z_pay_grp_com', 'x_use_kode_sum', 'use_kode_sum', '`use_kode_sum`', '`use_kode_sum`', 16, -1, FALSE, '`use_kode_sum`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->use_kode_sum->Sortable = TRUE; // Allow sort
		$this->use_kode_sum->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['use_kode_sum'] = &$this->use_kode_sum;

		// id_kode_sum
		$this->id_kode_sum = new cField('z_pay_grp_com', 'z_pay_grp_com', 'x_id_kode_sum', 'id_kode_sum', '`id_kode_sum`', '`id_kode_sum`', 2, -1, FALSE, '`id_kode_sum`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->id_kode_sum->Sortable = TRUE; // Allow sort
		$this->id_kode_sum->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_kode_sum'] = &$this->id_kode_sum;

		// operator_sum
		$this->operator_sum = new cField('z_pay_grp_com', 'z_pay_grp_com', 'x_operator_sum', 'operator_sum', '`operator_sum`', '`operator_sum`', 200, -1, FALSE, '`operator_sum`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->operator_sum->Sortable = TRUE; // Allow sort
		$this->fields['operator_sum'] = &$this->operator_sum;

		// konstanta_sum
		$this->konstanta_sum = new cField('z_pay_grp_com', 'z_pay_grp_com', 'x_konstanta_sum', 'konstanta_sum', '`konstanta_sum`', '`konstanta_sum`', 4, -1, FALSE, '`konstanta_sum`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->konstanta_sum->Sortable = TRUE; // Allow sort
		$this->konstanta_sum->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['konstanta_sum'] = &$this->konstanta_sum;

		// operator_sum2
		$this->operator_sum2 = new cField('z_pay_grp_com', 'z_pay_grp_com', 'x_operator_sum2', 'operator_sum2', '`operator_sum2`', '`operator_sum2`', 200, -1, FALSE, '`operator_sum2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->operator_sum2->Sortable = TRUE; // Allow sort
		$this->fields['operator_sum2'] = &$this->operator_sum2;

		// nilai_rp
		$this->nilai_rp = new cField('z_pay_grp_com', 'z_pay_grp_com', 'x_nilai_rp', 'nilai_rp', '`nilai_rp`', '`nilai_rp`', 4, -1, FALSE, '`nilai_rp`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nilai_rp->Sortable = TRUE; // Allow sort
		$this->nilai_rp->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['nilai_rp'] = &$this->nilai_rp;

		// hitung
		$this->hitung = new cField('z_pay_grp_com', 'z_pay_grp_com', 'x_hitung', 'hitung', '`hitung`', '`hitung`', 16, -1, FALSE, '`hitung`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->hitung->Sortable = TRUE; // Allow sort
		$this->hitung->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['hitung'] = &$this->hitung;

		// jenis
		$this->jenis = new cField('z_pay_grp_com', 'z_pay_grp_com', 'x_jenis', 'jenis', '`jenis`', '`jenis`', 16, -1, FALSE, '`jenis`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jenis->Sortable = TRUE; // Allow sort
		$this->jenis->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['jenis'] = &$this->jenis;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`z_pay_grp_com`";
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
			if (array_key_exists('grp_id', $rs))
				ew_AddFilter($where, ew_QuotedName('grp_id', $this->DBID) . '=' . ew_QuotedValue($rs['grp_id'], $this->grp_id->FldDataType, $this->DBID));
			if (array_key_exists('com_id', $rs))
				ew_AddFilter($where, ew_QuotedName('com_id', $this->DBID) . '=' . ew_QuotedValue($rs['com_id'], $this->com_id->FldDataType, $this->DBID));
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
		$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`grp_id` = @grp_id@ AND `com_id` = @com_id@ AND `no_urut_ref` = @no_urut_ref@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->grp_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@grp_id@", ew_AdjustSql($this->grp_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		if (!is_numeric($this->com_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@com_id@", ew_AdjustSql($this->com_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "z_pay_grp_comlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "z_pay_grp_comlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("z_pay_grp_comview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("z_pay_grp_comview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "z_pay_grp_comadd.php?" . $this->UrlParm($parm);
		else
			$url = "z_pay_grp_comadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("z_pay_grp_comedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("z_pay_grp_comadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("z_pay_grp_comdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "grp_id:" . ew_VarToJson($this->grp_id->CurrentValue, "number", "'");
		$json .= ",com_id:" . ew_VarToJson($this->com_id->CurrentValue, "number", "'");
		$json .= ",no_urut_ref:" . ew_VarToJson($this->no_urut_ref->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->grp_id->CurrentValue)) {
			$sUrl .= "grp_id=" . urlencode($this->grp_id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->com_id->CurrentValue)) {
			$sUrl .= "&com_id=" . urlencode($this->com_id->CurrentValue);
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
			if ($isPost && isset($_POST["grp_id"]))
				$arKey[] = ew_StripSlashes($_POST["grp_id"]);
			elseif (isset($_GET["grp_id"]))
				$arKey[] = ew_StripSlashes($_GET["grp_id"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["com_id"]))
				$arKey[] = ew_StripSlashes($_POST["com_id"]);
			elseif (isset($_GET["com_id"]))
				$arKey[] = ew_StripSlashes($_GET["com_id"]);
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
				if (!is_numeric($key[0])) // grp_id
					continue;
				if (!is_numeric($key[1])) // com_id
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
			$this->grp_id->CurrentValue = $key[0];
			$this->com_id->CurrentValue = $key[1];
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
		$this->grp_id->setDbValue($rs->fields('grp_id'));
		$this->com_id->setDbValue($rs->fields('com_id'));
		$this->no_urut_ref->setDbValue($rs->fields('no_urut_ref'));
		$this->use_if_sum->setDbValue($rs->fields('use_if_sum'));
		$this->use_kode_if->setDbValue($rs->fields('use_kode_if'));
		$this->id_kode_if->setDbValue($rs->fields('id_kode_if'));
		$this->min_if->setDbValue($rs->fields('min_if'));
		$this->max_if->setDbValue($rs->fields('max_if'));
		$this->use_sum->setDbValue($rs->fields('use_sum'));
		$this->use_kode_sum->setDbValue($rs->fields('use_kode_sum'));
		$this->id_kode_sum->setDbValue($rs->fields('id_kode_sum'));
		$this->operator_sum->setDbValue($rs->fields('operator_sum'));
		$this->konstanta_sum->setDbValue($rs->fields('konstanta_sum'));
		$this->operator_sum2->setDbValue($rs->fields('operator_sum2'));
		$this->nilai_rp->setDbValue($rs->fields('nilai_rp'));
		$this->hitung->setDbValue($rs->fields('hitung'));
		$this->jenis->setDbValue($rs->fields('jenis'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// grp_id
		// com_id
		// no_urut_ref
		// use_if_sum
		// use_kode_if
		// id_kode_if
		// min_if
		// max_if
		// use_sum
		// use_kode_sum
		// id_kode_sum
		// operator_sum
		// konstanta_sum
		// operator_sum2
		// nilai_rp
		// hitung
		// jenis
		// grp_id

		$this->grp_id->ViewValue = $this->grp_id->CurrentValue;
		$this->grp_id->ViewCustomAttributes = "";

		// com_id
		$this->com_id->ViewValue = $this->com_id->CurrentValue;
		$this->com_id->ViewCustomAttributes = "";

		// no_urut_ref
		$this->no_urut_ref->ViewValue = $this->no_urut_ref->CurrentValue;
		$this->no_urut_ref->ViewCustomAttributes = "";

		// use_if_sum
		$this->use_if_sum->ViewValue = $this->use_if_sum->CurrentValue;
		$this->use_if_sum->ViewCustomAttributes = "";

		// use_kode_if
		$this->use_kode_if->ViewValue = $this->use_kode_if->CurrentValue;
		$this->use_kode_if->ViewCustomAttributes = "";

		// id_kode_if
		$this->id_kode_if->ViewValue = $this->id_kode_if->CurrentValue;
		$this->id_kode_if->ViewCustomAttributes = "";

		// min_if
		$this->min_if->ViewValue = $this->min_if->CurrentValue;
		$this->min_if->ViewCustomAttributes = "";

		// max_if
		$this->max_if->ViewValue = $this->max_if->CurrentValue;
		$this->max_if->ViewCustomAttributes = "";

		// use_sum
		$this->use_sum->ViewValue = $this->use_sum->CurrentValue;
		$this->use_sum->ViewCustomAttributes = "";

		// use_kode_sum
		$this->use_kode_sum->ViewValue = $this->use_kode_sum->CurrentValue;
		$this->use_kode_sum->ViewCustomAttributes = "";

		// id_kode_sum
		$this->id_kode_sum->ViewValue = $this->id_kode_sum->CurrentValue;
		$this->id_kode_sum->ViewCustomAttributes = "";

		// operator_sum
		$this->operator_sum->ViewValue = $this->operator_sum->CurrentValue;
		$this->operator_sum->ViewCustomAttributes = "";

		// konstanta_sum
		$this->konstanta_sum->ViewValue = $this->konstanta_sum->CurrentValue;
		$this->konstanta_sum->ViewCustomAttributes = "";

		// operator_sum2
		$this->operator_sum2->ViewValue = $this->operator_sum2->CurrentValue;
		$this->operator_sum2->ViewCustomAttributes = "";

		// nilai_rp
		$this->nilai_rp->ViewValue = $this->nilai_rp->CurrentValue;
		$this->nilai_rp->ViewCustomAttributes = "";

		// hitung
		$this->hitung->ViewValue = $this->hitung->CurrentValue;
		$this->hitung->ViewCustomAttributes = "";

		// jenis
		$this->jenis->ViewValue = $this->jenis->CurrentValue;
		$this->jenis->ViewCustomAttributes = "";

		// grp_id
		$this->grp_id->LinkCustomAttributes = "";
		$this->grp_id->HrefValue = "";
		$this->grp_id->TooltipValue = "";

		// com_id
		$this->com_id->LinkCustomAttributes = "";
		$this->com_id->HrefValue = "";
		$this->com_id->TooltipValue = "";

		// no_urut_ref
		$this->no_urut_ref->LinkCustomAttributes = "";
		$this->no_urut_ref->HrefValue = "";
		$this->no_urut_ref->TooltipValue = "";

		// use_if_sum
		$this->use_if_sum->LinkCustomAttributes = "";
		$this->use_if_sum->HrefValue = "";
		$this->use_if_sum->TooltipValue = "";

		// use_kode_if
		$this->use_kode_if->LinkCustomAttributes = "";
		$this->use_kode_if->HrefValue = "";
		$this->use_kode_if->TooltipValue = "";

		// id_kode_if
		$this->id_kode_if->LinkCustomAttributes = "";
		$this->id_kode_if->HrefValue = "";
		$this->id_kode_if->TooltipValue = "";

		// min_if
		$this->min_if->LinkCustomAttributes = "";
		$this->min_if->HrefValue = "";
		$this->min_if->TooltipValue = "";

		// max_if
		$this->max_if->LinkCustomAttributes = "";
		$this->max_if->HrefValue = "";
		$this->max_if->TooltipValue = "";

		// use_sum
		$this->use_sum->LinkCustomAttributes = "";
		$this->use_sum->HrefValue = "";
		$this->use_sum->TooltipValue = "";

		// use_kode_sum
		$this->use_kode_sum->LinkCustomAttributes = "";
		$this->use_kode_sum->HrefValue = "";
		$this->use_kode_sum->TooltipValue = "";

		// id_kode_sum
		$this->id_kode_sum->LinkCustomAttributes = "";
		$this->id_kode_sum->HrefValue = "";
		$this->id_kode_sum->TooltipValue = "";

		// operator_sum
		$this->operator_sum->LinkCustomAttributes = "";
		$this->operator_sum->HrefValue = "";
		$this->operator_sum->TooltipValue = "";

		// konstanta_sum
		$this->konstanta_sum->LinkCustomAttributes = "";
		$this->konstanta_sum->HrefValue = "";
		$this->konstanta_sum->TooltipValue = "";

		// operator_sum2
		$this->operator_sum2->LinkCustomAttributes = "";
		$this->operator_sum2->HrefValue = "";
		$this->operator_sum2->TooltipValue = "";

		// nilai_rp
		$this->nilai_rp->LinkCustomAttributes = "";
		$this->nilai_rp->HrefValue = "";
		$this->nilai_rp->TooltipValue = "";

		// hitung
		$this->hitung->LinkCustomAttributes = "";
		$this->hitung->HrefValue = "";
		$this->hitung->TooltipValue = "";

		// jenis
		$this->jenis->LinkCustomAttributes = "";
		$this->jenis->HrefValue = "";
		$this->jenis->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// grp_id
		$this->grp_id->EditAttrs["class"] = "form-control";
		$this->grp_id->EditCustomAttributes = "";
		$this->grp_id->EditValue = $this->grp_id->CurrentValue;
		$this->grp_id->ViewCustomAttributes = "";

		// com_id
		$this->com_id->EditAttrs["class"] = "form-control";
		$this->com_id->EditCustomAttributes = "";
		$this->com_id->EditValue = $this->com_id->CurrentValue;
		$this->com_id->ViewCustomAttributes = "";

		// no_urut_ref
		$this->no_urut_ref->EditAttrs["class"] = "form-control";
		$this->no_urut_ref->EditCustomAttributes = "";
		$this->no_urut_ref->EditValue = $this->no_urut_ref->CurrentValue;
		$this->no_urut_ref->ViewCustomAttributes = "";

		// use_if_sum
		$this->use_if_sum->EditAttrs["class"] = "form-control";
		$this->use_if_sum->EditCustomAttributes = "";
		$this->use_if_sum->EditValue = $this->use_if_sum->CurrentValue;
		$this->use_if_sum->PlaceHolder = ew_RemoveHtml($this->use_if_sum->FldCaption());

		// use_kode_if
		$this->use_kode_if->EditAttrs["class"] = "form-control";
		$this->use_kode_if->EditCustomAttributes = "";
		$this->use_kode_if->EditValue = $this->use_kode_if->CurrentValue;
		$this->use_kode_if->PlaceHolder = ew_RemoveHtml($this->use_kode_if->FldCaption());

		// id_kode_if
		$this->id_kode_if->EditAttrs["class"] = "form-control";
		$this->id_kode_if->EditCustomAttributes = "";
		$this->id_kode_if->EditValue = $this->id_kode_if->CurrentValue;
		$this->id_kode_if->PlaceHolder = ew_RemoveHtml($this->id_kode_if->FldCaption());

		// min_if
		$this->min_if->EditAttrs["class"] = "form-control";
		$this->min_if->EditCustomAttributes = "";
		$this->min_if->EditValue = $this->min_if->CurrentValue;
		$this->min_if->PlaceHolder = ew_RemoveHtml($this->min_if->FldCaption());
		if (strval($this->min_if->EditValue) <> "" && is_numeric($this->min_if->EditValue)) $this->min_if->EditValue = ew_FormatNumber($this->min_if->EditValue, -2, -1, -2, 0);

		// max_if
		$this->max_if->EditAttrs["class"] = "form-control";
		$this->max_if->EditCustomAttributes = "";
		$this->max_if->EditValue = $this->max_if->CurrentValue;
		$this->max_if->PlaceHolder = ew_RemoveHtml($this->max_if->FldCaption());
		if (strval($this->max_if->EditValue) <> "" && is_numeric($this->max_if->EditValue)) $this->max_if->EditValue = ew_FormatNumber($this->max_if->EditValue, -2, -1, -2, 0);

		// use_sum
		$this->use_sum->EditAttrs["class"] = "form-control";
		$this->use_sum->EditCustomAttributes = "";
		$this->use_sum->EditValue = $this->use_sum->CurrentValue;
		$this->use_sum->PlaceHolder = ew_RemoveHtml($this->use_sum->FldCaption());

		// use_kode_sum
		$this->use_kode_sum->EditAttrs["class"] = "form-control";
		$this->use_kode_sum->EditCustomAttributes = "";
		$this->use_kode_sum->EditValue = $this->use_kode_sum->CurrentValue;
		$this->use_kode_sum->PlaceHolder = ew_RemoveHtml($this->use_kode_sum->FldCaption());

		// id_kode_sum
		$this->id_kode_sum->EditAttrs["class"] = "form-control";
		$this->id_kode_sum->EditCustomAttributes = "";
		$this->id_kode_sum->EditValue = $this->id_kode_sum->CurrentValue;
		$this->id_kode_sum->PlaceHolder = ew_RemoveHtml($this->id_kode_sum->FldCaption());

		// operator_sum
		$this->operator_sum->EditAttrs["class"] = "form-control";
		$this->operator_sum->EditCustomAttributes = "";
		$this->operator_sum->EditValue = $this->operator_sum->CurrentValue;
		$this->operator_sum->PlaceHolder = ew_RemoveHtml($this->operator_sum->FldCaption());

		// konstanta_sum
		$this->konstanta_sum->EditAttrs["class"] = "form-control";
		$this->konstanta_sum->EditCustomAttributes = "";
		$this->konstanta_sum->EditValue = $this->konstanta_sum->CurrentValue;
		$this->konstanta_sum->PlaceHolder = ew_RemoveHtml($this->konstanta_sum->FldCaption());
		if (strval($this->konstanta_sum->EditValue) <> "" && is_numeric($this->konstanta_sum->EditValue)) $this->konstanta_sum->EditValue = ew_FormatNumber($this->konstanta_sum->EditValue, -2, -1, -2, 0);

		// operator_sum2
		$this->operator_sum2->EditAttrs["class"] = "form-control";
		$this->operator_sum2->EditCustomAttributes = "";
		$this->operator_sum2->EditValue = $this->operator_sum2->CurrentValue;
		$this->operator_sum2->PlaceHolder = ew_RemoveHtml($this->operator_sum2->FldCaption());

		// nilai_rp
		$this->nilai_rp->EditAttrs["class"] = "form-control";
		$this->nilai_rp->EditCustomAttributes = "";
		$this->nilai_rp->EditValue = $this->nilai_rp->CurrentValue;
		$this->nilai_rp->PlaceHolder = ew_RemoveHtml($this->nilai_rp->FldCaption());
		if (strval($this->nilai_rp->EditValue) <> "" && is_numeric($this->nilai_rp->EditValue)) $this->nilai_rp->EditValue = ew_FormatNumber($this->nilai_rp->EditValue, -2, -1, -2, 0);

		// hitung
		$this->hitung->EditAttrs["class"] = "form-control";
		$this->hitung->EditCustomAttributes = "";
		$this->hitung->EditValue = $this->hitung->CurrentValue;
		$this->hitung->PlaceHolder = ew_RemoveHtml($this->hitung->FldCaption());

		// jenis
		$this->jenis->EditAttrs["class"] = "form-control";
		$this->jenis->EditCustomAttributes = "";
		$this->jenis->EditValue = $this->jenis->CurrentValue;
		$this->jenis->PlaceHolder = ew_RemoveHtml($this->jenis->FldCaption());

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
					if ($this->grp_id->Exportable) $Doc->ExportCaption($this->grp_id);
					if ($this->com_id->Exportable) $Doc->ExportCaption($this->com_id);
					if ($this->no_urut_ref->Exportable) $Doc->ExportCaption($this->no_urut_ref);
					if ($this->use_if_sum->Exportable) $Doc->ExportCaption($this->use_if_sum);
					if ($this->use_kode_if->Exportable) $Doc->ExportCaption($this->use_kode_if);
					if ($this->id_kode_if->Exportable) $Doc->ExportCaption($this->id_kode_if);
					if ($this->min_if->Exportable) $Doc->ExportCaption($this->min_if);
					if ($this->max_if->Exportable) $Doc->ExportCaption($this->max_if);
					if ($this->use_sum->Exportable) $Doc->ExportCaption($this->use_sum);
					if ($this->use_kode_sum->Exportable) $Doc->ExportCaption($this->use_kode_sum);
					if ($this->id_kode_sum->Exportable) $Doc->ExportCaption($this->id_kode_sum);
					if ($this->operator_sum->Exportable) $Doc->ExportCaption($this->operator_sum);
					if ($this->konstanta_sum->Exportable) $Doc->ExportCaption($this->konstanta_sum);
					if ($this->operator_sum2->Exportable) $Doc->ExportCaption($this->operator_sum2);
					if ($this->nilai_rp->Exportable) $Doc->ExportCaption($this->nilai_rp);
					if ($this->hitung->Exportable) $Doc->ExportCaption($this->hitung);
					if ($this->jenis->Exportable) $Doc->ExportCaption($this->jenis);
				} else {
					if ($this->grp_id->Exportable) $Doc->ExportCaption($this->grp_id);
					if ($this->com_id->Exportable) $Doc->ExportCaption($this->com_id);
					if ($this->no_urut_ref->Exportable) $Doc->ExportCaption($this->no_urut_ref);
					if ($this->use_if_sum->Exportable) $Doc->ExportCaption($this->use_if_sum);
					if ($this->use_kode_if->Exportable) $Doc->ExportCaption($this->use_kode_if);
					if ($this->id_kode_if->Exportable) $Doc->ExportCaption($this->id_kode_if);
					if ($this->min_if->Exportable) $Doc->ExportCaption($this->min_if);
					if ($this->max_if->Exportable) $Doc->ExportCaption($this->max_if);
					if ($this->use_sum->Exportable) $Doc->ExportCaption($this->use_sum);
					if ($this->use_kode_sum->Exportable) $Doc->ExportCaption($this->use_kode_sum);
					if ($this->id_kode_sum->Exportable) $Doc->ExportCaption($this->id_kode_sum);
					if ($this->operator_sum->Exportable) $Doc->ExportCaption($this->operator_sum);
					if ($this->konstanta_sum->Exportable) $Doc->ExportCaption($this->konstanta_sum);
					if ($this->operator_sum2->Exportable) $Doc->ExportCaption($this->operator_sum2);
					if ($this->nilai_rp->Exportable) $Doc->ExportCaption($this->nilai_rp);
					if ($this->hitung->Exportable) $Doc->ExportCaption($this->hitung);
					if ($this->jenis->Exportable) $Doc->ExportCaption($this->jenis);
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
						if ($this->grp_id->Exportable) $Doc->ExportField($this->grp_id);
						if ($this->com_id->Exportable) $Doc->ExportField($this->com_id);
						if ($this->no_urut_ref->Exportable) $Doc->ExportField($this->no_urut_ref);
						if ($this->use_if_sum->Exportable) $Doc->ExportField($this->use_if_sum);
						if ($this->use_kode_if->Exportable) $Doc->ExportField($this->use_kode_if);
						if ($this->id_kode_if->Exportable) $Doc->ExportField($this->id_kode_if);
						if ($this->min_if->Exportable) $Doc->ExportField($this->min_if);
						if ($this->max_if->Exportable) $Doc->ExportField($this->max_if);
						if ($this->use_sum->Exportable) $Doc->ExportField($this->use_sum);
						if ($this->use_kode_sum->Exportable) $Doc->ExportField($this->use_kode_sum);
						if ($this->id_kode_sum->Exportable) $Doc->ExportField($this->id_kode_sum);
						if ($this->operator_sum->Exportable) $Doc->ExportField($this->operator_sum);
						if ($this->konstanta_sum->Exportable) $Doc->ExportField($this->konstanta_sum);
						if ($this->operator_sum2->Exportable) $Doc->ExportField($this->operator_sum2);
						if ($this->nilai_rp->Exportable) $Doc->ExportField($this->nilai_rp);
						if ($this->hitung->Exportable) $Doc->ExportField($this->hitung);
						if ($this->jenis->Exportable) $Doc->ExportField($this->jenis);
					} else {
						if ($this->grp_id->Exportable) $Doc->ExportField($this->grp_id);
						if ($this->com_id->Exportable) $Doc->ExportField($this->com_id);
						if ($this->no_urut_ref->Exportable) $Doc->ExportField($this->no_urut_ref);
						if ($this->use_if_sum->Exportable) $Doc->ExportField($this->use_if_sum);
						if ($this->use_kode_if->Exportable) $Doc->ExportField($this->use_kode_if);
						if ($this->id_kode_if->Exportable) $Doc->ExportField($this->id_kode_if);
						if ($this->min_if->Exportable) $Doc->ExportField($this->min_if);
						if ($this->max_if->Exportable) $Doc->ExportField($this->max_if);
						if ($this->use_sum->Exportable) $Doc->ExportField($this->use_sum);
						if ($this->use_kode_sum->Exportable) $Doc->ExportField($this->use_kode_sum);
						if ($this->id_kode_sum->Exportable) $Doc->ExportField($this->id_kode_sum);
						if ($this->operator_sum->Exportable) $Doc->ExportField($this->operator_sum);
						if ($this->konstanta_sum->Exportable) $Doc->ExportField($this->konstanta_sum);
						if ($this->operator_sum2->Exportable) $Doc->ExportField($this->operator_sum2);
						if ($this->nilai_rp->Exportable) $Doc->ExportField($this->nilai_rp);
						if ($this->hitung->Exportable) $Doc->ExportField($this->hitung);
						if ($this->jenis->Exportable) $Doc->ExportField($this->jenis);
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
