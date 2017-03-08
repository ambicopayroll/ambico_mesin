<?php

// Global variable for table object
$jam_kerja = NULL;

//
// Table class for jam_kerja
//
class cjam_kerja extends cTable {
	var $jk_id;
	var $jk_name;
	var $jk_kode;
	var $use_set;
	var $jk_bcin;
	var $jk_cin;
	var $jk_ecin;
	var $jk_tol_late;
	var $jk_use_ist;
	var $jk_ist1;
	var $jk_ist2;
	var $jk_tol_early;
	var $jk_bcout;
	var $jk_cout;
	var $jk_ecout;
	var $use_eot;
	var $min_eot;
	var $max_eot;
	var $reduce_eot;
	var $jk_durasi;
	var $jk_countas;
	var $jk_min_countas;
	var $jk_ket;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'jam_kerja';
		$this->TableName = 'jam_kerja';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`jam_kerja`";
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

		// jk_id
		$this->jk_id = new cField('jam_kerja', 'jam_kerja', 'x_jk_id', 'jk_id', '`jk_id`', '`jk_id`', 3, -1, FALSE, '`jk_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jk_id->Sortable = TRUE; // Allow sort
		$this->jk_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['jk_id'] = &$this->jk_id;

		// jk_name
		$this->jk_name = new cField('jam_kerja', 'jam_kerja', 'x_jk_name', 'jk_name', '`jk_name`', '`jk_name`', 200, -1, FALSE, '`jk_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jk_name->Sortable = TRUE; // Allow sort
		$this->fields['jk_name'] = &$this->jk_name;

		// jk_kode
		$this->jk_kode = new cField('jam_kerja', 'jam_kerja', 'x_jk_kode', 'jk_kode', '`jk_kode`', '`jk_kode`', 200, -1, FALSE, '`jk_kode`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jk_kode->Sortable = TRUE; // Allow sort
		$this->fields['jk_kode'] = &$this->jk_kode;

		// use_set
		$this->use_set = new cField('jam_kerja', 'jam_kerja', 'x_use_set', 'use_set', '`use_set`', '`use_set`', 16, -1, FALSE, '`use_set`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->use_set->Sortable = TRUE; // Allow sort
		$this->use_set->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['use_set'] = &$this->use_set;

		// jk_bcin
		$this->jk_bcin = new cField('jam_kerja', 'jam_kerja', 'x_jk_bcin', 'jk_bcin', '`jk_bcin`', ew_CastDateFieldForLike('`jk_bcin`', 0, "DB"), 134, -1, FALSE, '`jk_bcin`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jk_bcin->Sortable = TRUE; // Allow sort
		$this->jk_bcin->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_TIME_SEPARATOR"], $Language->Phrase("IncorrectTime"));
		$this->fields['jk_bcin'] = &$this->jk_bcin;

		// jk_cin
		$this->jk_cin = new cField('jam_kerja', 'jam_kerja', 'x_jk_cin', 'jk_cin', '`jk_cin`', '`jk_cin`', 2, -1, FALSE, '`jk_cin`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jk_cin->Sortable = TRUE; // Allow sort
		$this->jk_cin->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['jk_cin'] = &$this->jk_cin;

		// jk_ecin
		$this->jk_ecin = new cField('jam_kerja', 'jam_kerja', 'x_jk_ecin', 'jk_ecin', '`jk_ecin`', '`jk_ecin`', 2, -1, FALSE, '`jk_ecin`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jk_ecin->Sortable = TRUE; // Allow sort
		$this->jk_ecin->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['jk_ecin'] = &$this->jk_ecin;

		// jk_tol_late
		$this->jk_tol_late = new cField('jam_kerja', 'jam_kerja', 'x_jk_tol_late', 'jk_tol_late', '`jk_tol_late`', '`jk_tol_late`', 2, -1, FALSE, '`jk_tol_late`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jk_tol_late->Sortable = TRUE; // Allow sort
		$this->jk_tol_late->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['jk_tol_late'] = &$this->jk_tol_late;

		// jk_use_ist
		$this->jk_use_ist = new cField('jam_kerja', 'jam_kerja', 'x_jk_use_ist', 'jk_use_ist', '`jk_use_ist`', '`jk_use_ist`', 16, -1, FALSE, '`jk_use_ist`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jk_use_ist->Sortable = TRUE; // Allow sort
		$this->jk_use_ist->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['jk_use_ist'] = &$this->jk_use_ist;

		// jk_ist1
		$this->jk_ist1 = new cField('jam_kerja', 'jam_kerja', 'x_jk_ist1', 'jk_ist1', '`jk_ist1`', ew_CastDateFieldForLike('`jk_ist1`', 0, "DB"), 134, -1, FALSE, '`jk_ist1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jk_ist1->Sortable = TRUE; // Allow sort
		$this->jk_ist1->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_TIME_SEPARATOR"], $Language->Phrase("IncorrectTime"));
		$this->fields['jk_ist1'] = &$this->jk_ist1;

		// jk_ist2
		$this->jk_ist2 = new cField('jam_kerja', 'jam_kerja', 'x_jk_ist2', 'jk_ist2', '`jk_ist2`', ew_CastDateFieldForLike('`jk_ist2`', 0, "DB"), 134, -1, FALSE, '`jk_ist2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jk_ist2->Sortable = TRUE; // Allow sort
		$this->jk_ist2->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_TIME_SEPARATOR"], $Language->Phrase("IncorrectTime"));
		$this->fields['jk_ist2'] = &$this->jk_ist2;

		// jk_tol_early
		$this->jk_tol_early = new cField('jam_kerja', 'jam_kerja', 'x_jk_tol_early', 'jk_tol_early', '`jk_tol_early`', '`jk_tol_early`', 2, -1, FALSE, '`jk_tol_early`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jk_tol_early->Sortable = TRUE; // Allow sort
		$this->jk_tol_early->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['jk_tol_early'] = &$this->jk_tol_early;

		// jk_bcout
		$this->jk_bcout = new cField('jam_kerja', 'jam_kerja', 'x_jk_bcout', 'jk_bcout', '`jk_bcout`', '`jk_bcout`', 2, -1, FALSE, '`jk_bcout`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jk_bcout->Sortable = TRUE; // Allow sort
		$this->jk_bcout->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['jk_bcout'] = &$this->jk_bcout;

		// jk_cout
		$this->jk_cout = new cField('jam_kerja', 'jam_kerja', 'x_jk_cout', 'jk_cout', '`jk_cout`', '`jk_cout`', 2, -1, FALSE, '`jk_cout`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jk_cout->Sortable = TRUE; // Allow sort
		$this->jk_cout->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['jk_cout'] = &$this->jk_cout;

		// jk_ecout
		$this->jk_ecout = new cField('jam_kerja', 'jam_kerja', 'x_jk_ecout', 'jk_ecout', '`jk_ecout`', ew_CastDateFieldForLike('`jk_ecout`', 0, "DB"), 134, -1, FALSE, '`jk_ecout`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jk_ecout->Sortable = TRUE; // Allow sort
		$this->jk_ecout->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_TIME_SEPARATOR"], $Language->Phrase("IncorrectTime"));
		$this->fields['jk_ecout'] = &$this->jk_ecout;

		// use_eot
		$this->use_eot = new cField('jam_kerja', 'jam_kerja', 'x_use_eot', 'use_eot', '`use_eot`', '`use_eot`', 16, -1, FALSE, '`use_eot`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->use_eot->Sortable = TRUE; // Allow sort
		$this->use_eot->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['use_eot'] = &$this->use_eot;

		// min_eot
		$this->min_eot = new cField('jam_kerja', 'jam_kerja', 'x_min_eot', 'min_eot', '`min_eot`', '`min_eot`', 2, -1, FALSE, '`min_eot`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->min_eot->Sortable = TRUE; // Allow sort
		$this->min_eot->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['min_eot'] = &$this->min_eot;

		// max_eot
		$this->max_eot = new cField('jam_kerja', 'jam_kerja', 'x_max_eot', 'max_eot', '`max_eot`', '`max_eot`', 2, -1, FALSE, '`max_eot`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->max_eot->Sortable = TRUE; // Allow sort
		$this->max_eot->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['max_eot'] = &$this->max_eot;

		// reduce_eot
		$this->reduce_eot = new cField('jam_kerja', 'jam_kerja', 'x_reduce_eot', 'reduce_eot', '`reduce_eot`', '`reduce_eot`', 2, -1, FALSE, '`reduce_eot`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->reduce_eot->Sortable = TRUE; // Allow sort
		$this->reduce_eot->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['reduce_eot'] = &$this->reduce_eot;

		// jk_durasi
		$this->jk_durasi = new cField('jam_kerja', 'jam_kerja', 'x_jk_durasi', 'jk_durasi', '`jk_durasi`', '`jk_durasi`', 16, -1, FALSE, '`jk_durasi`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jk_durasi->Sortable = TRUE; // Allow sort
		$this->jk_durasi->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['jk_durasi'] = &$this->jk_durasi;

		// jk_countas
		$this->jk_countas = new cField('jam_kerja', 'jam_kerja', 'x_jk_countas', 'jk_countas', '`jk_countas`', '`jk_countas`', 4, -1, FALSE, '`jk_countas`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jk_countas->Sortable = TRUE; // Allow sort
		$this->jk_countas->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['jk_countas'] = &$this->jk_countas;

		// jk_min_countas
		$this->jk_min_countas = new cField('jam_kerja', 'jam_kerja', 'x_jk_min_countas', 'jk_min_countas', '`jk_min_countas`', '`jk_min_countas`', 2, -1, FALSE, '`jk_min_countas`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jk_min_countas->Sortable = TRUE; // Allow sort
		$this->jk_min_countas->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['jk_min_countas'] = &$this->jk_min_countas;

		// jk_ket
		$this->jk_ket = new cField('jam_kerja', 'jam_kerja', 'x_jk_ket', 'jk_ket', '`jk_ket`', '`jk_ket`', 200, -1, FALSE, '`jk_ket`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jk_ket->Sortable = TRUE; // Allow sort
		$this->fields['jk_ket'] = &$this->jk_ket;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`jam_kerja`";
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
			if (array_key_exists('jk_id', $rs))
				ew_AddFilter($where, ew_QuotedName('jk_id', $this->DBID) . '=' . ew_QuotedValue($rs['jk_id'], $this->jk_id->FldDataType, $this->DBID));
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
		return "`jk_id` = @jk_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->jk_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@jk_id@", ew_AdjustSql($this->jk_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "jam_kerjalist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "jam_kerjalist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("jam_kerjaview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("jam_kerjaview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "jam_kerjaadd.php?" . $this->UrlParm($parm);
		else
			$url = "jam_kerjaadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("jam_kerjaedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("jam_kerjaadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("jam_kerjadelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "jk_id:" . ew_VarToJson($this->jk_id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->jk_id->CurrentValue)) {
			$sUrl .= "jk_id=" . urlencode($this->jk_id->CurrentValue);
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
			if ($isPost && isset($_POST["jk_id"]))
				$arKeys[] = ew_StripSlashes($_POST["jk_id"]);
			elseif (isset($_GET["jk_id"]))
				$arKeys[] = ew_StripSlashes($_GET["jk_id"]);
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
			$this->jk_id->CurrentValue = $key;
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
		$this->jk_id->setDbValue($rs->fields('jk_id'));
		$this->jk_name->setDbValue($rs->fields('jk_name'));
		$this->jk_kode->setDbValue($rs->fields('jk_kode'));
		$this->use_set->setDbValue($rs->fields('use_set'));
		$this->jk_bcin->setDbValue($rs->fields('jk_bcin'));
		$this->jk_cin->setDbValue($rs->fields('jk_cin'));
		$this->jk_ecin->setDbValue($rs->fields('jk_ecin'));
		$this->jk_tol_late->setDbValue($rs->fields('jk_tol_late'));
		$this->jk_use_ist->setDbValue($rs->fields('jk_use_ist'));
		$this->jk_ist1->setDbValue($rs->fields('jk_ist1'));
		$this->jk_ist2->setDbValue($rs->fields('jk_ist2'));
		$this->jk_tol_early->setDbValue($rs->fields('jk_tol_early'));
		$this->jk_bcout->setDbValue($rs->fields('jk_bcout'));
		$this->jk_cout->setDbValue($rs->fields('jk_cout'));
		$this->jk_ecout->setDbValue($rs->fields('jk_ecout'));
		$this->use_eot->setDbValue($rs->fields('use_eot'));
		$this->min_eot->setDbValue($rs->fields('min_eot'));
		$this->max_eot->setDbValue($rs->fields('max_eot'));
		$this->reduce_eot->setDbValue($rs->fields('reduce_eot'));
		$this->jk_durasi->setDbValue($rs->fields('jk_durasi'));
		$this->jk_countas->setDbValue($rs->fields('jk_countas'));
		$this->jk_min_countas->setDbValue($rs->fields('jk_min_countas'));
		$this->jk_ket->setDbValue($rs->fields('jk_ket'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// jk_id
		// jk_name
		// jk_kode
		// use_set
		// jk_bcin
		// jk_cin
		// jk_ecin
		// jk_tol_late
		// jk_use_ist
		// jk_ist1
		// jk_ist2
		// jk_tol_early
		// jk_bcout
		// jk_cout
		// jk_ecout
		// use_eot
		// min_eot
		// max_eot
		// reduce_eot
		// jk_durasi
		// jk_countas
		// jk_min_countas
		// jk_ket
		// jk_id

		$this->jk_id->ViewValue = $this->jk_id->CurrentValue;
		$this->jk_id->ViewCustomAttributes = "";

		// jk_name
		$this->jk_name->ViewValue = $this->jk_name->CurrentValue;
		$this->jk_name->ViewCustomAttributes = "";

		// jk_kode
		$this->jk_kode->ViewValue = $this->jk_kode->CurrentValue;
		$this->jk_kode->ViewCustomAttributes = "";

		// use_set
		$this->use_set->ViewValue = $this->use_set->CurrentValue;
		$this->use_set->ViewCustomAttributes = "";

		// jk_bcin
		$this->jk_bcin->ViewValue = $this->jk_bcin->CurrentValue;
		$this->jk_bcin->ViewCustomAttributes = "";

		// jk_cin
		$this->jk_cin->ViewValue = $this->jk_cin->CurrentValue;
		$this->jk_cin->ViewCustomAttributes = "";

		// jk_ecin
		$this->jk_ecin->ViewValue = $this->jk_ecin->CurrentValue;
		$this->jk_ecin->ViewCustomAttributes = "";

		// jk_tol_late
		$this->jk_tol_late->ViewValue = $this->jk_tol_late->CurrentValue;
		$this->jk_tol_late->ViewCustomAttributes = "";

		// jk_use_ist
		$this->jk_use_ist->ViewValue = $this->jk_use_ist->CurrentValue;
		$this->jk_use_ist->ViewCustomAttributes = "";

		// jk_ist1
		$this->jk_ist1->ViewValue = $this->jk_ist1->CurrentValue;
		$this->jk_ist1->ViewCustomAttributes = "";

		// jk_ist2
		$this->jk_ist2->ViewValue = $this->jk_ist2->CurrentValue;
		$this->jk_ist2->ViewCustomAttributes = "";

		// jk_tol_early
		$this->jk_tol_early->ViewValue = $this->jk_tol_early->CurrentValue;
		$this->jk_tol_early->ViewCustomAttributes = "";

		// jk_bcout
		$this->jk_bcout->ViewValue = $this->jk_bcout->CurrentValue;
		$this->jk_bcout->ViewCustomAttributes = "";

		// jk_cout
		$this->jk_cout->ViewValue = $this->jk_cout->CurrentValue;
		$this->jk_cout->ViewCustomAttributes = "";

		// jk_ecout
		$this->jk_ecout->ViewValue = $this->jk_ecout->CurrentValue;
		$this->jk_ecout->ViewCustomAttributes = "";

		// use_eot
		$this->use_eot->ViewValue = $this->use_eot->CurrentValue;
		$this->use_eot->ViewCustomAttributes = "";

		// min_eot
		$this->min_eot->ViewValue = $this->min_eot->CurrentValue;
		$this->min_eot->ViewCustomAttributes = "";

		// max_eot
		$this->max_eot->ViewValue = $this->max_eot->CurrentValue;
		$this->max_eot->ViewCustomAttributes = "";

		// reduce_eot
		$this->reduce_eot->ViewValue = $this->reduce_eot->CurrentValue;
		$this->reduce_eot->ViewCustomAttributes = "";

		// jk_durasi
		$this->jk_durasi->ViewValue = $this->jk_durasi->CurrentValue;
		$this->jk_durasi->ViewCustomAttributes = "";

		// jk_countas
		$this->jk_countas->ViewValue = $this->jk_countas->CurrentValue;
		$this->jk_countas->ViewCustomAttributes = "";

		// jk_min_countas
		$this->jk_min_countas->ViewValue = $this->jk_min_countas->CurrentValue;
		$this->jk_min_countas->ViewCustomAttributes = "";

		// jk_ket
		$this->jk_ket->ViewValue = $this->jk_ket->CurrentValue;
		$this->jk_ket->ViewCustomAttributes = "";

		// jk_id
		$this->jk_id->LinkCustomAttributes = "";
		$this->jk_id->HrefValue = "";
		$this->jk_id->TooltipValue = "";

		// jk_name
		$this->jk_name->LinkCustomAttributes = "";
		$this->jk_name->HrefValue = "";
		$this->jk_name->TooltipValue = "";

		// jk_kode
		$this->jk_kode->LinkCustomAttributes = "";
		$this->jk_kode->HrefValue = "";
		$this->jk_kode->TooltipValue = "";

		// use_set
		$this->use_set->LinkCustomAttributes = "";
		$this->use_set->HrefValue = "";
		$this->use_set->TooltipValue = "";

		// jk_bcin
		$this->jk_bcin->LinkCustomAttributes = "";
		$this->jk_bcin->HrefValue = "";
		$this->jk_bcin->TooltipValue = "";

		// jk_cin
		$this->jk_cin->LinkCustomAttributes = "";
		$this->jk_cin->HrefValue = "";
		$this->jk_cin->TooltipValue = "";

		// jk_ecin
		$this->jk_ecin->LinkCustomAttributes = "";
		$this->jk_ecin->HrefValue = "";
		$this->jk_ecin->TooltipValue = "";

		// jk_tol_late
		$this->jk_tol_late->LinkCustomAttributes = "";
		$this->jk_tol_late->HrefValue = "";
		$this->jk_tol_late->TooltipValue = "";

		// jk_use_ist
		$this->jk_use_ist->LinkCustomAttributes = "";
		$this->jk_use_ist->HrefValue = "";
		$this->jk_use_ist->TooltipValue = "";

		// jk_ist1
		$this->jk_ist1->LinkCustomAttributes = "";
		$this->jk_ist1->HrefValue = "";
		$this->jk_ist1->TooltipValue = "";

		// jk_ist2
		$this->jk_ist2->LinkCustomAttributes = "";
		$this->jk_ist2->HrefValue = "";
		$this->jk_ist2->TooltipValue = "";

		// jk_tol_early
		$this->jk_tol_early->LinkCustomAttributes = "";
		$this->jk_tol_early->HrefValue = "";
		$this->jk_tol_early->TooltipValue = "";

		// jk_bcout
		$this->jk_bcout->LinkCustomAttributes = "";
		$this->jk_bcout->HrefValue = "";
		$this->jk_bcout->TooltipValue = "";

		// jk_cout
		$this->jk_cout->LinkCustomAttributes = "";
		$this->jk_cout->HrefValue = "";
		$this->jk_cout->TooltipValue = "";

		// jk_ecout
		$this->jk_ecout->LinkCustomAttributes = "";
		$this->jk_ecout->HrefValue = "";
		$this->jk_ecout->TooltipValue = "";

		// use_eot
		$this->use_eot->LinkCustomAttributes = "";
		$this->use_eot->HrefValue = "";
		$this->use_eot->TooltipValue = "";

		// min_eot
		$this->min_eot->LinkCustomAttributes = "";
		$this->min_eot->HrefValue = "";
		$this->min_eot->TooltipValue = "";

		// max_eot
		$this->max_eot->LinkCustomAttributes = "";
		$this->max_eot->HrefValue = "";
		$this->max_eot->TooltipValue = "";

		// reduce_eot
		$this->reduce_eot->LinkCustomAttributes = "";
		$this->reduce_eot->HrefValue = "";
		$this->reduce_eot->TooltipValue = "";

		// jk_durasi
		$this->jk_durasi->LinkCustomAttributes = "";
		$this->jk_durasi->HrefValue = "";
		$this->jk_durasi->TooltipValue = "";

		// jk_countas
		$this->jk_countas->LinkCustomAttributes = "";
		$this->jk_countas->HrefValue = "";
		$this->jk_countas->TooltipValue = "";

		// jk_min_countas
		$this->jk_min_countas->LinkCustomAttributes = "";
		$this->jk_min_countas->HrefValue = "";
		$this->jk_min_countas->TooltipValue = "";

		// jk_ket
		$this->jk_ket->LinkCustomAttributes = "";
		$this->jk_ket->HrefValue = "";
		$this->jk_ket->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// jk_id
		$this->jk_id->EditAttrs["class"] = "form-control";
		$this->jk_id->EditCustomAttributes = "";
		$this->jk_id->EditValue = $this->jk_id->CurrentValue;
		$this->jk_id->ViewCustomAttributes = "";

		// jk_name
		$this->jk_name->EditAttrs["class"] = "form-control";
		$this->jk_name->EditCustomAttributes = "";
		$this->jk_name->EditValue = $this->jk_name->CurrentValue;
		$this->jk_name->PlaceHolder = ew_RemoveHtml($this->jk_name->FldCaption());

		// jk_kode
		$this->jk_kode->EditAttrs["class"] = "form-control";
		$this->jk_kode->EditCustomAttributes = "";
		$this->jk_kode->EditValue = $this->jk_kode->CurrentValue;
		$this->jk_kode->PlaceHolder = ew_RemoveHtml($this->jk_kode->FldCaption());

		// use_set
		$this->use_set->EditAttrs["class"] = "form-control";
		$this->use_set->EditCustomAttributes = "";
		$this->use_set->EditValue = $this->use_set->CurrentValue;
		$this->use_set->PlaceHolder = ew_RemoveHtml($this->use_set->FldCaption());

		// jk_bcin
		$this->jk_bcin->EditAttrs["class"] = "form-control";
		$this->jk_bcin->EditCustomAttributes = "";
		$this->jk_bcin->EditValue = $this->jk_bcin->CurrentValue;
		$this->jk_bcin->PlaceHolder = ew_RemoveHtml($this->jk_bcin->FldCaption());

		// jk_cin
		$this->jk_cin->EditAttrs["class"] = "form-control";
		$this->jk_cin->EditCustomAttributes = "";
		$this->jk_cin->EditValue = $this->jk_cin->CurrentValue;
		$this->jk_cin->PlaceHolder = ew_RemoveHtml($this->jk_cin->FldCaption());

		// jk_ecin
		$this->jk_ecin->EditAttrs["class"] = "form-control";
		$this->jk_ecin->EditCustomAttributes = "";
		$this->jk_ecin->EditValue = $this->jk_ecin->CurrentValue;
		$this->jk_ecin->PlaceHolder = ew_RemoveHtml($this->jk_ecin->FldCaption());

		// jk_tol_late
		$this->jk_tol_late->EditAttrs["class"] = "form-control";
		$this->jk_tol_late->EditCustomAttributes = "";
		$this->jk_tol_late->EditValue = $this->jk_tol_late->CurrentValue;
		$this->jk_tol_late->PlaceHolder = ew_RemoveHtml($this->jk_tol_late->FldCaption());

		// jk_use_ist
		$this->jk_use_ist->EditAttrs["class"] = "form-control";
		$this->jk_use_ist->EditCustomAttributes = "";
		$this->jk_use_ist->EditValue = $this->jk_use_ist->CurrentValue;
		$this->jk_use_ist->PlaceHolder = ew_RemoveHtml($this->jk_use_ist->FldCaption());

		// jk_ist1
		$this->jk_ist1->EditAttrs["class"] = "form-control";
		$this->jk_ist1->EditCustomAttributes = "";
		$this->jk_ist1->EditValue = $this->jk_ist1->CurrentValue;
		$this->jk_ist1->PlaceHolder = ew_RemoveHtml($this->jk_ist1->FldCaption());

		// jk_ist2
		$this->jk_ist2->EditAttrs["class"] = "form-control";
		$this->jk_ist2->EditCustomAttributes = "";
		$this->jk_ist2->EditValue = $this->jk_ist2->CurrentValue;
		$this->jk_ist2->PlaceHolder = ew_RemoveHtml($this->jk_ist2->FldCaption());

		// jk_tol_early
		$this->jk_tol_early->EditAttrs["class"] = "form-control";
		$this->jk_tol_early->EditCustomAttributes = "";
		$this->jk_tol_early->EditValue = $this->jk_tol_early->CurrentValue;
		$this->jk_tol_early->PlaceHolder = ew_RemoveHtml($this->jk_tol_early->FldCaption());

		// jk_bcout
		$this->jk_bcout->EditAttrs["class"] = "form-control";
		$this->jk_bcout->EditCustomAttributes = "";
		$this->jk_bcout->EditValue = $this->jk_bcout->CurrentValue;
		$this->jk_bcout->PlaceHolder = ew_RemoveHtml($this->jk_bcout->FldCaption());

		// jk_cout
		$this->jk_cout->EditAttrs["class"] = "form-control";
		$this->jk_cout->EditCustomAttributes = "";
		$this->jk_cout->EditValue = $this->jk_cout->CurrentValue;
		$this->jk_cout->PlaceHolder = ew_RemoveHtml($this->jk_cout->FldCaption());

		// jk_ecout
		$this->jk_ecout->EditAttrs["class"] = "form-control";
		$this->jk_ecout->EditCustomAttributes = "";
		$this->jk_ecout->EditValue = $this->jk_ecout->CurrentValue;
		$this->jk_ecout->PlaceHolder = ew_RemoveHtml($this->jk_ecout->FldCaption());

		// use_eot
		$this->use_eot->EditAttrs["class"] = "form-control";
		$this->use_eot->EditCustomAttributes = "";
		$this->use_eot->EditValue = $this->use_eot->CurrentValue;
		$this->use_eot->PlaceHolder = ew_RemoveHtml($this->use_eot->FldCaption());

		// min_eot
		$this->min_eot->EditAttrs["class"] = "form-control";
		$this->min_eot->EditCustomAttributes = "";
		$this->min_eot->EditValue = $this->min_eot->CurrentValue;
		$this->min_eot->PlaceHolder = ew_RemoveHtml($this->min_eot->FldCaption());

		// max_eot
		$this->max_eot->EditAttrs["class"] = "form-control";
		$this->max_eot->EditCustomAttributes = "";
		$this->max_eot->EditValue = $this->max_eot->CurrentValue;
		$this->max_eot->PlaceHolder = ew_RemoveHtml($this->max_eot->FldCaption());

		// reduce_eot
		$this->reduce_eot->EditAttrs["class"] = "form-control";
		$this->reduce_eot->EditCustomAttributes = "";
		$this->reduce_eot->EditValue = $this->reduce_eot->CurrentValue;
		$this->reduce_eot->PlaceHolder = ew_RemoveHtml($this->reduce_eot->FldCaption());

		// jk_durasi
		$this->jk_durasi->EditAttrs["class"] = "form-control";
		$this->jk_durasi->EditCustomAttributes = "";
		$this->jk_durasi->EditValue = $this->jk_durasi->CurrentValue;
		$this->jk_durasi->PlaceHolder = ew_RemoveHtml($this->jk_durasi->FldCaption());

		// jk_countas
		$this->jk_countas->EditAttrs["class"] = "form-control";
		$this->jk_countas->EditCustomAttributes = "";
		$this->jk_countas->EditValue = $this->jk_countas->CurrentValue;
		$this->jk_countas->PlaceHolder = ew_RemoveHtml($this->jk_countas->FldCaption());
		if (strval($this->jk_countas->EditValue) <> "" && is_numeric($this->jk_countas->EditValue)) $this->jk_countas->EditValue = ew_FormatNumber($this->jk_countas->EditValue, -2, -1, -2, 0);

		// jk_min_countas
		$this->jk_min_countas->EditAttrs["class"] = "form-control";
		$this->jk_min_countas->EditCustomAttributes = "";
		$this->jk_min_countas->EditValue = $this->jk_min_countas->CurrentValue;
		$this->jk_min_countas->PlaceHolder = ew_RemoveHtml($this->jk_min_countas->FldCaption());

		// jk_ket
		$this->jk_ket->EditAttrs["class"] = "form-control";
		$this->jk_ket->EditCustomAttributes = "";
		$this->jk_ket->EditValue = $this->jk_ket->CurrentValue;
		$this->jk_ket->PlaceHolder = ew_RemoveHtml($this->jk_ket->FldCaption());

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
					if ($this->jk_id->Exportable) $Doc->ExportCaption($this->jk_id);
					if ($this->jk_name->Exportable) $Doc->ExportCaption($this->jk_name);
					if ($this->jk_kode->Exportable) $Doc->ExportCaption($this->jk_kode);
					if ($this->use_set->Exportable) $Doc->ExportCaption($this->use_set);
					if ($this->jk_bcin->Exportable) $Doc->ExportCaption($this->jk_bcin);
					if ($this->jk_cin->Exportable) $Doc->ExportCaption($this->jk_cin);
					if ($this->jk_ecin->Exportable) $Doc->ExportCaption($this->jk_ecin);
					if ($this->jk_tol_late->Exportable) $Doc->ExportCaption($this->jk_tol_late);
					if ($this->jk_use_ist->Exportable) $Doc->ExportCaption($this->jk_use_ist);
					if ($this->jk_ist1->Exportable) $Doc->ExportCaption($this->jk_ist1);
					if ($this->jk_ist2->Exportable) $Doc->ExportCaption($this->jk_ist2);
					if ($this->jk_tol_early->Exportable) $Doc->ExportCaption($this->jk_tol_early);
					if ($this->jk_bcout->Exportable) $Doc->ExportCaption($this->jk_bcout);
					if ($this->jk_cout->Exportable) $Doc->ExportCaption($this->jk_cout);
					if ($this->jk_ecout->Exportable) $Doc->ExportCaption($this->jk_ecout);
					if ($this->use_eot->Exportable) $Doc->ExportCaption($this->use_eot);
					if ($this->min_eot->Exportable) $Doc->ExportCaption($this->min_eot);
					if ($this->max_eot->Exportable) $Doc->ExportCaption($this->max_eot);
					if ($this->reduce_eot->Exportable) $Doc->ExportCaption($this->reduce_eot);
					if ($this->jk_durasi->Exportable) $Doc->ExportCaption($this->jk_durasi);
					if ($this->jk_countas->Exportable) $Doc->ExportCaption($this->jk_countas);
					if ($this->jk_min_countas->Exportable) $Doc->ExportCaption($this->jk_min_countas);
					if ($this->jk_ket->Exportable) $Doc->ExportCaption($this->jk_ket);
				} else {
					if ($this->jk_id->Exportable) $Doc->ExportCaption($this->jk_id);
					if ($this->jk_name->Exportable) $Doc->ExportCaption($this->jk_name);
					if ($this->jk_kode->Exportable) $Doc->ExportCaption($this->jk_kode);
					if ($this->use_set->Exportable) $Doc->ExportCaption($this->use_set);
					if ($this->jk_bcin->Exportable) $Doc->ExportCaption($this->jk_bcin);
					if ($this->jk_cin->Exportable) $Doc->ExportCaption($this->jk_cin);
					if ($this->jk_ecin->Exportable) $Doc->ExportCaption($this->jk_ecin);
					if ($this->jk_tol_late->Exportable) $Doc->ExportCaption($this->jk_tol_late);
					if ($this->jk_use_ist->Exportable) $Doc->ExportCaption($this->jk_use_ist);
					if ($this->jk_ist1->Exportable) $Doc->ExportCaption($this->jk_ist1);
					if ($this->jk_ist2->Exportable) $Doc->ExportCaption($this->jk_ist2);
					if ($this->jk_tol_early->Exportable) $Doc->ExportCaption($this->jk_tol_early);
					if ($this->jk_bcout->Exportable) $Doc->ExportCaption($this->jk_bcout);
					if ($this->jk_cout->Exportable) $Doc->ExportCaption($this->jk_cout);
					if ($this->jk_ecout->Exportable) $Doc->ExportCaption($this->jk_ecout);
					if ($this->use_eot->Exportable) $Doc->ExportCaption($this->use_eot);
					if ($this->min_eot->Exportable) $Doc->ExportCaption($this->min_eot);
					if ($this->max_eot->Exportable) $Doc->ExportCaption($this->max_eot);
					if ($this->reduce_eot->Exportable) $Doc->ExportCaption($this->reduce_eot);
					if ($this->jk_durasi->Exportable) $Doc->ExportCaption($this->jk_durasi);
					if ($this->jk_countas->Exportable) $Doc->ExportCaption($this->jk_countas);
					if ($this->jk_min_countas->Exportable) $Doc->ExportCaption($this->jk_min_countas);
					if ($this->jk_ket->Exportable) $Doc->ExportCaption($this->jk_ket);
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
						if ($this->jk_id->Exportable) $Doc->ExportField($this->jk_id);
						if ($this->jk_name->Exportable) $Doc->ExportField($this->jk_name);
						if ($this->jk_kode->Exportable) $Doc->ExportField($this->jk_kode);
						if ($this->use_set->Exportable) $Doc->ExportField($this->use_set);
						if ($this->jk_bcin->Exportable) $Doc->ExportField($this->jk_bcin);
						if ($this->jk_cin->Exportable) $Doc->ExportField($this->jk_cin);
						if ($this->jk_ecin->Exportable) $Doc->ExportField($this->jk_ecin);
						if ($this->jk_tol_late->Exportable) $Doc->ExportField($this->jk_tol_late);
						if ($this->jk_use_ist->Exportable) $Doc->ExportField($this->jk_use_ist);
						if ($this->jk_ist1->Exportable) $Doc->ExportField($this->jk_ist1);
						if ($this->jk_ist2->Exportable) $Doc->ExportField($this->jk_ist2);
						if ($this->jk_tol_early->Exportable) $Doc->ExportField($this->jk_tol_early);
						if ($this->jk_bcout->Exportable) $Doc->ExportField($this->jk_bcout);
						if ($this->jk_cout->Exportable) $Doc->ExportField($this->jk_cout);
						if ($this->jk_ecout->Exportable) $Doc->ExportField($this->jk_ecout);
						if ($this->use_eot->Exportable) $Doc->ExportField($this->use_eot);
						if ($this->min_eot->Exportable) $Doc->ExportField($this->min_eot);
						if ($this->max_eot->Exportable) $Doc->ExportField($this->max_eot);
						if ($this->reduce_eot->Exportable) $Doc->ExportField($this->reduce_eot);
						if ($this->jk_durasi->Exportable) $Doc->ExportField($this->jk_durasi);
						if ($this->jk_countas->Exportable) $Doc->ExportField($this->jk_countas);
						if ($this->jk_min_countas->Exportable) $Doc->ExportField($this->jk_min_countas);
						if ($this->jk_ket->Exportable) $Doc->ExportField($this->jk_ket);
					} else {
						if ($this->jk_id->Exportable) $Doc->ExportField($this->jk_id);
						if ($this->jk_name->Exportable) $Doc->ExportField($this->jk_name);
						if ($this->jk_kode->Exportable) $Doc->ExportField($this->jk_kode);
						if ($this->use_set->Exportable) $Doc->ExportField($this->use_set);
						if ($this->jk_bcin->Exportable) $Doc->ExportField($this->jk_bcin);
						if ($this->jk_cin->Exportable) $Doc->ExportField($this->jk_cin);
						if ($this->jk_ecin->Exportable) $Doc->ExportField($this->jk_ecin);
						if ($this->jk_tol_late->Exportable) $Doc->ExportField($this->jk_tol_late);
						if ($this->jk_use_ist->Exportable) $Doc->ExportField($this->jk_use_ist);
						if ($this->jk_ist1->Exportable) $Doc->ExportField($this->jk_ist1);
						if ($this->jk_ist2->Exportable) $Doc->ExportField($this->jk_ist2);
						if ($this->jk_tol_early->Exportable) $Doc->ExportField($this->jk_tol_early);
						if ($this->jk_bcout->Exportable) $Doc->ExportField($this->jk_bcout);
						if ($this->jk_cout->Exportable) $Doc->ExportField($this->jk_cout);
						if ($this->jk_ecout->Exportable) $Doc->ExportField($this->jk_ecout);
						if ($this->use_eot->Exportable) $Doc->ExportField($this->use_eot);
						if ($this->min_eot->Exportable) $Doc->ExportField($this->min_eot);
						if ($this->max_eot->Exportable) $Doc->ExportField($this->max_eot);
						if ($this->reduce_eot->Exportable) $Doc->ExportField($this->reduce_eot);
						if ($this->jk_durasi->Exportable) $Doc->ExportField($this->jk_durasi);
						if ($this->jk_countas->Exportable) $Doc->ExportField($this->jk_countas);
						if ($this->jk_min_countas->Exportable) $Doc->ExportField($this->jk_min_countas);
						if ($this->jk_ket->Exportable) $Doc->ExportField($this->jk_ket);
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
