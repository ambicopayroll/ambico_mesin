<?php

// Global variable for table object
$zx_kredit_m = NULL;

//
// Table class for zx_kredit_m
//
class czx_kredit_m extends cTable {
	var $id_kredit;
	var $no_kredit;
	var $tgl_kredit;
	var $emp_id_auto;
	var $krd_id;
	var $cara_hitung;
	var $tot_kredit;
	var $saldo_aw;
	var $suku_bunga;
	var $periode_bulan;
	var $angs_pokok;
	var $angs_pertama;
	var $tot_debet;
	var $tot_angs_pokok;
	var $tot_bunga;
	var $def_pembulatan;
	var $jumlah_piutang;
	var $approv_by;
	var $keterangan;
	var $status;
	var $status_lunas;
	var $lastupdate_date;
	var $lastupdate_user;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'zx_kredit_m';
		$this->TableName = 'zx_kredit_m';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`zx_kredit_m`";
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

		// id_kredit
		$this->id_kredit = new cField('zx_kredit_m', 'zx_kredit_m', 'x_id_kredit', 'id_kredit', '`id_kredit`', '`id_kredit`', 3, -1, FALSE, '`id_kredit`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->id_kredit->Sortable = TRUE; // Allow sort
		$this->id_kredit->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_kredit'] = &$this->id_kredit;

		// no_kredit
		$this->no_kredit = new cField('zx_kredit_m', 'zx_kredit_m', 'x_no_kredit', 'no_kredit', '`no_kredit`', '`no_kredit`', 200, -1, FALSE, '`no_kredit`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->no_kredit->Sortable = TRUE; // Allow sort
		$this->fields['no_kredit'] = &$this->no_kredit;

		// tgl_kredit
		$this->tgl_kredit = new cField('zx_kredit_m', 'zx_kredit_m', 'x_tgl_kredit', 'tgl_kredit', '`tgl_kredit`', ew_CastDateFieldForLike('`tgl_kredit`', 0, "DB"), 133, 0, FALSE, '`tgl_kredit`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tgl_kredit->Sortable = TRUE; // Allow sort
		$this->tgl_kredit->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['tgl_kredit'] = &$this->tgl_kredit;

		// emp_id_auto
		$this->emp_id_auto = new cField('zx_kredit_m', 'zx_kredit_m', 'x_emp_id_auto', 'emp_id_auto', '`emp_id_auto`', '`emp_id_auto`', 3, -1, FALSE, '`emp_id_auto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->emp_id_auto->Sortable = TRUE; // Allow sort
		$this->emp_id_auto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['emp_id_auto'] = &$this->emp_id_auto;

		// krd_id
		$this->krd_id = new cField('zx_kredit_m', 'zx_kredit_m', 'x_krd_id', 'krd_id', '`krd_id`', '`krd_id`', 16, -1, FALSE, '`krd_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->krd_id->Sortable = TRUE; // Allow sort
		$this->krd_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['krd_id'] = &$this->krd_id;

		// cara_hitung
		$this->cara_hitung = new cField('zx_kredit_m', 'zx_kredit_m', 'x_cara_hitung', 'cara_hitung', '`cara_hitung`', '`cara_hitung`', 16, -1, FALSE, '`cara_hitung`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->cara_hitung->Sortable = TRUE; // Allow sort
		$this->cara_hitung->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cara_hitung'] = &$this->cara_hitung;

		// tot_kredit
		$this->tot_kredit = new cField('zx_kredit_m', 'zx_kredit_m', 'x_tot_kredit', 'tot_kredit', '`tot_kredit`', '`tot_kredit`', 4, -1, FALSE, '`tot_kredit`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tot_kredit->Sortable = TRUE; // Allow sort
		$this->tot_kredit->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['tot_kredit'] = &$this->tot_kredit;

		// saldo_aw
		$this->saldo_aw = new cField('zx_kredit_m', 'zx_kredit_m', 'x_saldo_aw', 'saldo_aw', '`saldo_aw`', '`saldo_aw`', 4, -1, FALSE, '`saldo_aw`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->saldo_aw->Sortable = TRUE; // Allow sort
		$this->saldo_aw->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['saldo_aw'] = &$this->saldo_aw;

		// suku_bunga
		$this->suku_bunga = new cField('zx_kredit_m', 'zx_kredit_m', 'x_suku_bunga', 'suku_bunga', '`suku_bunga`', '`suku_bunga`', 5, -1, FALSE, '`suku_bunga`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->suku_bunga->Sortable = TRUE; // Allow sort
		$this->suku_bunga->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['suku_bunga'] = &$this->suku_bunga;

		// periode_bulan
		$this->periode_bulan = new cField('zx_kredit_m', 'zx_kredit_m', 'x_periode_bulan', 'periode_bulan', '`periode_bulan`', '`periode_bulan`', 2, -1, FALSE, '`periode_bulan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->periode_bulan->Sortable = TRUE; // Allow sort
		$this->periode_bulan->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['periode_bulan'] = &$this->periode_bulan;

		// angs_pokok
		$this->angs_pokok = new cField('zx_kredit_m', 'zx_kredit_m', 'x_angs_pokok', 'angs_pokok', '`angs_pokok`', '`angs_pokok`', 4, -1, FALSE, '`angs_pokok`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->angs_pokok->Sortable = TRUE; // Allow sort
		$this->angs_pokok->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['angs_pokok'] = &$this->angs_pokok;

		// angs_pertama
		$this->angs_pertama = new cField('zx_kredit_m', 'zx_kredit_m', 'x_angs_pertama', 'angs_pertama', '`angs_pertama`', ew_CastDateFieldForLike('`angs_pertama`', 0, "DB"), 133, 0, FALSE, '`angs_pertama`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->angs_pertama->Sortable = TRUE; // Allow sort
		$this->angs_pertama->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['angs_pertama'] = &$this->angs_pertama;

		// tot_debet
		$this->tot_debet = new cField('zx_kredit_m', 'zx_kredit_m', 'x_tot_debet', 'tot_debet', '`tot_debet`', '`tot_debet`', 4, -1, FALSE, '`tot_debet`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tot_debet->Sortable = TRUE; // Allow sort
		$this->tot_debet->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['tot_debet'] = &$this->tot_debet;

		// tot_angs_pokok
		$this->tot_angs_pokok = new cField('zx_kredit_m', 'zx_kredit_m', 'x_tot_angs_pokok', 'tot_angs_pokok', '`tot_angs_pokok`', '`tot_angs_pokok`', 4, -1, FALSE, '`tot_angs_pokok`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tot_angs_pokok->Sortable = TRUE; // Allow sort
		$this->tot_angs_pokok->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['tot_angs_pokok'] = &$this->tot_angs_pokok;

		// tot_bunga
		$this->tot_bunga = new cField('zx_kredit_m', 'zx_kredit_m', 'x_tot_bunga', 'tot_bunga', '`tot_bunga`', '`tot_bunga`', 4, -1, FALSE, '`tot_bunga`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tot_bunga->Sortable = TRUE; // Allow sort
		$this->tot_bunga->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['tot_bunga'] = &$this->tot_bunga;

		// def_pembulatan
		$this->def_pembulatan = new cField('zx_kredit_m', 'zx_kredit_m', 'x_def_pembulatan', 'def_pembulatan', '`def_pembulatan`', '`def_pembulatan`', 2, -1, FALSE, '`def_pembulatan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->def_pembulatan->Sortable = TRUE; // Allow sort
		$this->def_pembulatan->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['def_pembulatan'] = &$this->def_pembulatan;

		// jumlah_piutang
		$this->jumlah_piutang = new cField('zx_kredit_m', 'zx_kredit_m', 'x_jumlah_piutang', 'jumlah_piutang', '`jumlah_piutang`', '`jumlah_piutang`', 4, -1, FALSE, '`jumlah_piutang`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jumlah_piutang->Sortable = TRUE; // Allow sort
		$this->jumlah_piutang->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['jumlah_piutang'] = &$this->jumlah_piutang;

		// approv_by
		$this->approv_by = new cField('zx_kredit_m', 'zx_kredit_m', 'x_approv_by', 'approv_by', '`approv_by`', '`approv_by`', 200, -1, FALSE, '`approv_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->approv_by->Sortable = TRUE; // Allow sort
		$this->fields['approv_by'] = &$this->approv_by;

		// keterangan
		$this->keterangan = new cField('zx_kredit_m', 'zx_kredit_m', 'x_keterangan', 'keterangan', '`keterangan`', '`keterangan`', 201, -1, FALSE, '`keterangan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->keterangan->Sortable = TRUE; // Allow sort
		$this->fields['keterangan'] = &$this->keterangan;

		// status
		$this->status = new cField('zx_kredit_m', 'zx_kredit_m', 'x_status', 'status', '`status`', '`status`', 16, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->status->Sortable = TRUE; // Allow sort
		$this->status->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['status'] = &$this->status;

		// status_lunas
		$this->status_lunas = new cField('zx_kredit_m', 'zx_kredit_m', 'x_status_lunas', 'status_lunas', '`status_lunas`', '`status_lunas`', 16, -1, FALSE, '`status_lunas`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->status_lunas->Sortable = TRUE; // Allow sort
		$this->status_lunas->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['status_lunas'] = &$this->status_lunas;

		// lastupdate_date
		$this->lastupdate_date = new cField('zx_kredit_m', 'zx_kredit_m', 'x_lastupdate_date', 'lastupdate_date', '`lastupdate_date`', ew_CastDateFieldForLike('`lastupdate_date`', 0, "DB"), 135, 0, FALSE, '`lastupdate_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->lastupdate_date->Sortable = TRUE; // Allow sort
		$this->lastupdate_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['lastupdate_date'] = &$this->lastupdate_date;

		// lastupdate_user
		$this->lastupdate_user = new cField('zx_kredit_m', 'zx_kredit_m', 'x_lastupdate_user', 'lastupdate_user', '`lastupdate_user`', '`lastupdate_user`', 200, -1, FALSE, '`lastupdate_user`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`zx_kredit_m`";
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
			if (array_key_exists('id_kredit', $rs))
				ew_AddFilter($where, ew_QuotedName('id_kredit', $this->DBID) . '=' . ew_QuotedValue($rs['id_kredit'], $this->id_kredit->FldDataType, $this->DBID));
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
		return "`id_kredit` = @id_kredit@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id_kredit->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id_kredit@", ew_AdjustSql($this->id_kredit->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "zx_kredit_mlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "zx_kredit_mlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("zx_kredit_mview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("zx_kredit_mview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "zx_kredit_madd.php?" . $this->UrlParm($parm);
		else
			$url = "zx_kredit_madd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("zx_kredit_medit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("zx_kredit_madd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("zx_kredit_mdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "id_kredit:" . ew_VarToJson($this->id_kredit->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id_kredit->CurrentValue)) {
			$sUrl .= "id_kredit=" . urlencode($this->id_kredit->CurrentValue);
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
			if ($isPost && isset($_POST["id_kredit"]))
				$arKeys[] = ew_StripSlashes($_POST["id_kredit"]);
			elseif (isset($_GET["id_kredit"]))
				$arKeys[] = ew_StripSlashes($_GET["id_kredit"]);
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
			$this->id_kredit->CurrentValue = $key;
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
		$this->id_kredit->setDbValue($rs->fields('id_kredit'));
		$this->no_kredit->setDbValue($rs->fields('no_kredit'));
		$this->tgl_kredit->setDbValue($rs->fields('tgl_kredit'));
		$this->emp_id_auto->setDbValue($rs->fields('emp_id_auto'));
		$this->krd_id->setDbValue($rs->fields('krd_id'));
		$this->cara_hitung->setDbValue($rs->fields('cara_hitung'));
		$this->tot_kredit->setDbValue($rs->fields('tot_kredit'));
		$this->saldo_aw->setDbValue($rs->fields('saldo_aw'));
		$this->suku_bunga->setDbValue($rs->fields('suku_bunga'));
		$this->periode_bulan->setDbValue($rs->fields('periode_bulan'));
		$this->angs_pokok->setDbValue($rs->fields('angs_pokok'));
		$this->angs_pertama->setDbValue($rs->fields('angs_pertama'));
		$this->tot_debet->setDbValue($rs->fields('tot_debet'));
		$this->tot_angs_pokok->setDbValue($rs->fields('tot_angs_pokok'));
		$this->tot_bunga->setDbValue($rs->fields('tot_bunga'));
		$this->def_pembulatan->setDbValue($rs->fields('def_pembulatan'));
		$this->jumlah_piutang->setDbValue($rs->fields('jumlah_piutang'));
		$this->approv_by->setDbValue($rs->fields('approv_by'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
		$this->status->setDbValue($rs->fields('status'));
		$this->status_lunas->setDbValue($rs->fields('status_lunas'));
		$this->lastupdate_date->setDbValue($rs->fields('lastupdate_date'));
		$this->lastupdate_user->setDbValue($rs->fields('lastupdate_user'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id_kredit
		// no_kredit
		// tgl_kredit
		// emp_id_auto
		// krd_id
		// cara_hitung
		// tot_kredit
		// saldo_aw
		// suku_bunga
		// periode_bulan
		// angs_pokok
		// angs_pertama
		// tot_debet
		// tot_angs_pokok
		// tot_bunga
		// def_pembulatan
		// jumlah_piutang
		// approv_by
		// keterangan
		// status
		// status_lunas
		// lastupdate_date
		// lastupdate_user
		// id_kredit

		$this->id_kredit->ViewValue = $this->id_kredit->CurrentValue;
		$this->id_kredit->ViewCustomAttributes = "";

		// no_kredit
		$this->no_kredit->ViewValue = $this->no_kredit->CurrentValue;
		$this->no_kredit->ViewCustomAttributes = "";

		// tgl_kredit
		$this->tgl_kredit->ViewValue = $this->tgl_kredit->CurrentValue;
		$this->tgl_kredit->ViewValue = ew_FormatDateTime($this->tgl_kredit->ViewValue, 0);
		$this->tgl_kredit->ViewCustomAttributes = "";

		// emp_id_auto
		$this->emp_id_auto->ViewValue = $this->emp_id_auto->CurrentValue;
		$this->emp_id_auto->ViewCustomAttributes = "";

		// krd_id
		$this->krd_id->ViewValue = $this->krd_id->CurrentValue;
		$this->krd_id->ViewCustomAttributes = "";

		// cara_hitung
		$this->cara_hitung->ViewValue = $this->cara_hitung->CurrentValue;
		$this->cara_hitung->ViewCustomAttributes = "";

		// tot_kredit
		$this->tot_kredit->ViewValue = $this->tot_kredit->CurrentValue;
		$this->tot_kredit->ViewCustomAttributes = "";

		// saldo_aw
		$this->saldo_aw->ViewValue = $this->saldo_aw->CurrentValue;
		$this->saldo_aw->ViewCustomAttributes = "";

		// suku_bunga
		$this->suku_bunga->ViewValue = $this->suku_bunga->CurrentValue;
		$this->suku_bunga->ViewCustomAttributes = "";

		// periode_bulan
		$this->periode_bulan->ViewValue = $this->periode_bulan->CurrentValue;
		$this->periode_bulan->ViewCustomAttributes = "";

		// angs_pokok
		$this->angs_pokok->ViewValue = $this->angs_pokok->CurrentValue;
		$this->angs_pokok->ViewCustomAttributes = "";

		// angs_pertama
		$this->angs_pertama->ViewValue = $this->angs_pertama->CurrentValue;
		$this->angs_pertama->ViewValue = ew_FormatDateTime($this->angs_pertama->ViewValue, 0);
		$this->angs_pertama->ViewCustomAttributes = "";

		// tot_debet
		$this->tot_debet->ViewValue = $this->tot_debet->CurrentValue;
		$this->tot_debet->ViewCustomAttributes = "";

		// tot_angs_pokok
		$this->tot_angs_pokok->ViewValue = $this->tot_angs_pokok->CurrentValue;
		$this->tot_angs_pokok->ViewCustomAttributes = "";

		// tot_bunga
		$this->tot_bunga->ViewValue = $this->tot_bunga->CurrentValue;
		$this->tot_bunga->ViewCustomAttributes = "";

		// def_pembulatan
		$this->def_pembulatan->ViewValue = $this->def_pembulatan->CurrentValue;
		$this->def_pembulatan->ViewCustomAttributes = "";

		// jumlah_piutang
		$this->jumlah_piutang->ViewValue = $this->jumlah_piutang->CurrentValue;
		$this->jumlah_piutang->ViewCustomAttributes = "";

		// approv_by
		$this->approv_by->ViewValue = $this->approv_by->CurrentValue;
		$this->approv_by->ViewCustomAttributes = "";

		// keterangan
		$this->keterangan->ViewValue = $this->keterangan->CurrentValue;
		$this->keterangan->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		$this->status->ViewCustomAttributes = "";

		// status_lunas
		$this->status_lunas->ViewValue = $this->status_lunas->CurrentValue;
		$this->status_lunas->ViewCustomAttributes = "";

		// lastupdate_date
		$this->lastupdate_date->ViewValue = $this->lastupdate_date->CurrentValue;
		$this->lastupdate_date->ViewValue = ew_FormatDateTime($this->lastupdate_date->ViewValue, 0);
		$this->lastupdate_date->ViewCustomAttributes = "";

		// lastupdate_user
		$this->lastupdate_user->ViewValue = $this->lastupdate_user->CurrentValue;
		$this->lastupdate_user->ViewCustomAttributes = "";

		// id_kredit
		$this->id_kredit->LinkCustomAttributes = "";
		$this->id_kredit->HrefValue = "";
		$this->id_kredit->TooltipValue = "";

		// no_kredit
		$this->no_kredit->LinkCustomAttributes = "";
		$this->no_kredit->HrefValue = "";
		$this->no_kredit->TooltipValue = "";

		// tgl_kredit
		$this->tgl_kredit->LinkCustomAttributes = "";
		$this->tgl_kredit->HrefValue = "";
		$this->tgl_kredit->TooltipValue = "";

		// emp_id_auto
		$this->emp_id_auto->LinkCustomAttributes = "";
		$this->emp_id_auto->HrefValue = "";
		$this->emp_id_auto->TooltipValue = "";

		// krd_id
		$this->krd_id->LinkCustomAttributes = "";
		$this->krd_id->HrefValue = "";
		$this->krd_id->TooltipValue = "";

		// cara_hitung
		$this->cara_hitung->LinkCustomAttributes = "";
		$this->cara_hitung->HrefValue = "";
		$this->cara_hitung->TooltipValue = "";

		// tot_kredit
		$this->tot_kredit->LinkCustomAttributes = "";
		$this->tot_kredit->HrefValue = "";
		$this->tot_kredit->TooltipValue = "";

		// saldo_aw
		$this->saldo_aw->LinkCustomAttributes = "";
		$this->saldo_aw->HrefValue = "";
		$this->saldo_aw->TooltipValue = "";

		// suku_bunga
		$this->suku_bunga->LinkCustomAttributes = "";
		$this->suku_bunga->HrefValue = "";
		$this->suku_bunga->TooltipValue = "";

		// periode_bulan
		$this->periode_bulan->LinkCustomAttributes = "";
		$this->periode_bulan->HrefValue = "";
		$this->periode_bulan->TooltipValue = "";

		// angs_pokok
		$this->angs_pokok->LinkCustomAttributes = "";
		$this->angs_pokok->HrefValue = "";
		$this->angs_pokok->TooltipValue = "";

		// angs_pertama
		$this->angs_pertama->LinkCustomAttributes = "";
		$this->angs_pertama->HrefValue = "";
		$this->angs_pertama->TooltipValue = "";

		// tot_debet
		$this->tot_debet->LinkCustomAttributes = "";
		$this->tot_debet->HrefValue = "";
		$this->tot_debet->TooltipValue = "";

		// tot_angs_pokok
		$this->tot_angs_pokok->LinkCustomAttributes = "";
		$this->tot_angs_pokok->HrefValue = "";
		$this->tot_angs_pokok->TooltipValue = "";

		// tot_bunga
		$this->tot_bunga->LinkCustomAttributes = "";
		$this->tot_bunga->HrefValue = "";
		$this->tot_bunga->TooltipValue = "";

		// def_pembulatan
		$this->def_pembulatan->LinkCustomAttributes = "";
		$this->def_pembulatan->HrefValue = "";
		$this->def_pembulatan->TooltipValue = "";

		// jumlah_piutang
		$this->jumlah_piutang->LinkCustomAttributes = "";
		$this->jumlah_piutang->HrefValue = "";
		$this->jumlah_piutang->TooltipValue = "";

		// approv_by
		$this->approv_by->LinkCustomAttributes = "";
		$this->approv_by->HrefValue = "";
		$this->approv_by->TooltipValue = "";

		// keterangan
		$this->keterangan->LinkCustomAttributes = "";
		$this->keterangan->HrefValue = "";
		$this->keterangan->TooltipValue = "";

		// status
		$this->status->LinkCustomAttributes = "";
		$this->status->HrefValue = "";
		$this->status->TooltipValue = "";

		// status_lunas
		$this->status_lunas->LinkCustomAttributes = "";
		$this->status_lunas->HrefValue = "";
		$this->status_lunas->TooltipValue = "";

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

		// id_kredit
		$this->id_kredit->EditAttrs["class"] = "form-control";
		$this->id_kredit->EditCustomAttributes = "";
		$this->id_kredit->EditValue = $this->id_kredit->CurrentValue;
		$this->id_kredit->ViewCustomAttributes = "";

		// no_kredit
		$this->no_kredit->EditAttrs["class"] = "form-control";
		$this->no_kredit->EditCustomAttributes = "";
		$this->no_kredit->EditValue = $this->no_kredit->CurrentValue;
		$this->no_kredit->PlaceHolder = ew_RemoveHtml($this->no_kredit->FldCaption());

		// tgl_kredit
		$this->tgl_kredit->EditAttrs["class"] = "form-control";
		$this->tgl_kredit->EditCustomAttributes = "";
		$this->tgl_kredit->EditValue = ew_FormatDateTime($this->tgl_kredit->CurrentValue, 8);
		$this->tgl_kredit->PlaceHolder = ew_RemoveHtml($this->tgl_kredit->FldCaption());

		// emp_id_auto
		$this->emp_id_auto->EditAttrs["class"] = "form-control";
		$this->emp_id_auto->EditCustomAttributes = "";
		$this->emp_id_auto->EditValue = $this->emp_id_auto->CurrentValue;
		$this->emp_id_auto->PlaceHolder = ew_RemoveHtml($this->emp_id_auto->FldCaption());

		// krd_id
		$this->krd_id->EditAttrs["class"] = "form-control";
		$this->krd_id->EditCustomAttributes = "";
		$this->krd_id->EditValue = $this->krd_id->CurrentValue;
		$this->krd_id->PlaceHolder = ew_RemoveHtml($this->krd_id->FldCaption());

		// cara_hitung
		$this->cara_hitung->EditAttrs["class"] = "form-control";
		$this->cara_hitung->EditCustomAttributes = "";
		$this->cara_hitung->EditValue = $this->cara_hitung->CurrentValue;
		$this->cara_hitung->PlaceHolder = ew_RemoveHtml($this->cara_hitung->FldCaption());

		// tot_kredit
		$this->tot_kredit->EditAttrs["class"] = "form-control";
		$this->tot_kredit->EditCustomAttributes = "";
		$this->tot_kredit->EditValue = $this->tot_kredit->CurrentValue;
		$this->tot_kredit->PlaceHolder = ew_RemoveHtml($this->tot_kredit->FldCaption());
		if (strval($this->tot_kredit->EditValue) <> "" && is_numeric($this->tot_kredit->EditValue)) $this->tot_kredit->EditValue = ew_FormatNumber($this->tot_kredit->EditValue, -2, -1, -2, 0);

		// saldo_aw
		$this->saldo_aw->EditAttrs["class"] = "form-control";
		$this->saldo_aw->EditCustomAttributes = "";
		$this->saldo_aw->EditValue = $this->saldo_aw->CurrentValue;
		$this->saldo_aw->PlaceHolder = ew_RemoveHtml($this->saldo_aw->FldCaption());
		if (strval($this->saldo_aw->EditValue) <> "" && is_numeric($this->saldo_aw->EditValue)) $this->saldo_aw->EditValue = ew_FormatNumber($this->saldo_aw->EditValue, -2, -1, -2, 0);

		// suku_bunga
		$this->suku_bunga->EditAttrs["class"] = "form-control";
		$this->suku_bunga->EditCustomAttributes = "";
		$this->suku_bunga->EditValue = $this->suku_bunga->CurrentValue;
		$this->suku_bunga->PlaceHolder = ew_RemoveHtml($this->suku_bunga->FldCaption());
		if (strval($this->suku_bunga->EditValue) <> "" && is_numeric($this->suku_bunga->EditValue)) $this->suku_bunga->EditValue = ew_FormatNumber($this->suku_bunga->EditValue, -2, -1, -2, 0);

		// periode_bulan
		$this->periode_bulan->EditAttrs["class"] = "form-control";
		$this->periode_bulan->EditCustomAttributes = "";
		$this->periode_bulan->EditValue = $this->periode_bulan->CurrentValue;
		$this->periode_bulan->PlaceHolder = ew_RemoveHtml($this->periode_bulan->FldCaption());

		// angs_pokok
		$this->angs_pokok->EditAttrs["class"] = "form-control";
		$this->angs_pokok->EditCustomAttributes = "";
		$this->angs_pokok->EditValue = $this->angs_pokok->CurrentValue;
		$this->angs_pokok->PlaceHolder = ew_RemoveHtml($this->angs_pokok->FldCaption());
		if (strval($this->angs_pokok->EditValue) <> "" && is_numeric($this->angs_pokok->EditValue)) $this->angs_pokok->EditValue = ew_FormatNumber($this->angs_pokok->EditValue, -2, -1, -2, 0);

		// angs_pertama
		$this->angs_pertama->EditAttrs["class"] = "form-control";
		$this->angs_pertama->EditCustomAttributes = "";
		$this->angs_pertama->EditValue = ew_FormatDateTime($this->angs_pertama->CurrentValue, 8);
		$this->angs_pertama->PlaceHolder = ew_RemoveHtml($this->angs_pertama->FldCaption());

		// tot_debet
		$this->tot_debet->EditAttrs["class"] = "form-control";
		$this->tot_debet->EditCustomAttributes = "";
		$this->tot_debet->EditValue = $this->tot_debet->CurrentValue;
		$this->tot_debet->PlaceHolder = ew_RemoveHtml($this->tot_debet->FldCaption());
		if (strval($this->tot_debet->EditValue) <> "" && is_numeric($this->tot_debet->EditValue)) $this->tot_debet->EditValue = ew_FormatNumber($this->tot_debet->EditValue, -2, -1, -2, 0);

		// tot_angs_pokok
		$this->tot_angs_pokok->EditAttrs["class"] = "form-control";
		$this->tot_angs_pokok->EditCustomAttributes = "";
		$this->tot_angs_pokok->EditValue = $this->tot_angs_pokok->CurrentValue;
		$this->tot_angs_pokok->PlaceHolder = ew_RemoveHtml($this->tot_angs_pokok->FldCaption());
		if (strval($this->tot_angs_pokok->EditValue) <> "" && is_numeric($this->tot_angs_pokok->EditValue)) $this->tot_angs_pokok->EditValue = ew_FormatNumber($this->tot_angs_pokok->EditValue, -2, -1, -2, 0);

		// tot_bunga
		$this->tot_bunga->EditAttrs["class"] = "form-control";
		$this->tot_bunga->EditCustomAttributes = "";
		$this->tot_bunga->EditValue = $this->tot_bunga->CurrentValue;
		$this->tot_bunga->PlaceHolder = ew_RemoveHtml($this->tot_bunga->FldCaption());
		if (strval($this->tot_bunga->EditValue) <> "" && is_numeric($this->tot_bunga->EditValue)) $this->tot_bunga->EditValue = ew_FormatNumber($this->tot_bunga->EditValue, -2, -1, -2, 0);

		// def_pembulatan
		$this->def_pembulatan->EditAttrs["class"] = "form-control";
		$this->def_pembulatan->EditCustomAttributes = "";
		$this->def_pembulatan->EditValue = $this->def_pembulatan->CurrentValue;
		$this->def_pembulatan->PlaceHolder = ew_RemoveHtml($this->def_pembulatan->FldCaption());

		// jumlah_piutang
		$this->jumlah_piutang->EditAttrs["class"] = "form-control";
		$this->jumlah_piutang->EditCustomAttributes = "";
		$this->jumlah_piutang->EditValue = $this->jumlah_piutang->CurrentValue;
		$this->jumlah_piutang->PlaceHolder = ew_RemoveHtml($this->jumlah_piutang->FldCaption());
		if (strval($this->jumlah_piutang->EditValue) <> "" && is_numeric($this->jumlah_piutang->EditValue)) $this->jumlah_piutang->EditValue = ew_FormatNumber($this->jumlah_piutang->EditValue, -2, -1, -2, 0);

		// approv_by
		$this->approv_by->EditAttrs["class"] = "form-control";
		$this->approv_by->EditCustomAttributes = "";
		$this->approv_by->EditValue = $this->approv_by->CurrentValue;
		$this->approv_by->PlaceHolder = ew_RemoveHtml($this->approv_by->FldCaption());

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

		// status_lunas
		$this->status_lunas->EditAttrs["class"] = "form-control";
		$this->status_lunas->EditCustomAttributes = "";
		$this->status_lunas->EditValue = $this->status_lunas->CurrentValue;
		$this->status_lunas->PlaceHolder = ew_RemoveHtml($this->status_lunas->FldCaption());

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
					if ($this->id_kredit->Exportable) $Doc->ExportCaption($this->id_kredit);
					if ($this->no_kredit->Exportable) $Doc->ExportCaption($this->no_kredit);
					if ($this->tgl_kredit->Exportable) $Doc->ExportCaption($this->tgl_kredit);
					if ($this->emp_id_auto->Exportable) $Doc->ExportCaption($this->emp_id_auto);
					if ($this->krd_id->Exportable) $Doc->ExportCaption($this->krd_id);
					if ($this->cara_hitung->Exportable) $Doc->ExportCaption($this->cara_hitung);
					if ($this->tot_kredit->Exportable) $Doc->ExportCaption($this->tot_kredit);
					if ($this->saldo_aw->Exportable) $Doc->ExportCaption($this->saldo_aw);
					if ($this->suku_bunga->Exportable) $Doc->ExportCaption($this->suku_bunga);
					if ($this->periode_bulan->Exportable) $Doc->ExportCaption($this->periode_bulan);
					if ($this->angs_pokok->Exportable) $Doc->ExportCaption($this->angs_pokok);
					if ($this->angs_pertama->Exportable) $Doc->ExportCaption($this->angs_pertama);
					if ($this->tot_debet->Exportable) $Doc->ExportCaption($this->tot_debet);
					if ($this->tot_angs_pokok->Exportable) $Doc->ExportCaption($this->tot_angs_pokok);
					if ($this->tot_bunga->Exportable) $Doc->ExportCaption($this->tot_bunga);
					if ($this->def_pembulatan->Exportable) $Doc->ExportCaption($this->def_pembulatan);
					if ($this->jumlah_piutang->Exportable) $Doc->ExportCaption($this->jumlah_piutang);
					if ($this->approv_by->Exportable) $Doc->ExportCaption($this->approv_by);
					if ($this->keterangan->Exportable) $Doc->ExportCaption($this->keterangan);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->status_lunas->Exportable) $Doc->ExportCaption($this->status_lunas);
					if ($this->lastupdate_date->Exportable) $Doc->ExportCaption($this->lastupdate_date);
					if ($this->lastupdate_user->Exportable) $Doc->ExportCaption($this->lastupdate_user);
				} else {
					if ($this->id_kredit->Exportable) $Doc->ExportCaption($this->id_kredit);
					if ($this->no_kredit->Exportable) $Doc->ExportCaption($this->no_kredit);
					if ($this->tgl_kredit->Exportable) $Doc->ExportCaption($this->tgl_kredit);
					if ($this->emp_id_auto->Exportable) $Doc->ExportCaption($this->emp_id_auto);
					if ($this->krd_id->Exportable) $Doc->ExportCaption($this->krd_id);
					if ($this->cara_hitung->Exportable) $Doc->ExportCaption($this->cara_hitung);
					if ($this->tot_kredit->Exportable) $Doc->ExportCaption($this->tot_kredit);
					if ($this->saldo_aw->Exportable) $Doc->ExportCaption($this->saldo_aw);
					if ($this->suku_bunga->Exportable) $Doc->ExportCaption($this->suku_bunga);
					if ($this->periode_bulan->Exportable) $Doc->ExportCaption($this->periode_bulan);
					if ($this->angs_pokok->Exportable) $Doc->ExportCaption($this->angs_pokok);
					if ($this->angs_pertama->Exportable) $Doc->ExportCaption($this->angs_pertama);
					if ($this->tot_debet->Exportable) $Doc->ExportCaption($this->tot_debet);
					if ($this->tot_angs_pokok->Exportable) $Doc->ExportCaption($this->tot_angs_pokok);
					if ($this->tot_bunga->Exportable) $Doc->ExportCaption($this->tot_bunga);
					if ($this->def_pembulatan->Exportable) $Doc->ExportCaption($this->def_pembulatan);
					if ($this->jumlah_piutang->Exportable) $Doc->ExportCaption($this->jumlah_piutang);
					if ($this->approv_by->Exportable) $Doc->ExportCaption($this->approv_by);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->status_lunas->Exportable) $Doc->ExportCaption($this->status_lunas);
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
						if ($this->id_kredit->Exportable) $Doc->ExportField($this->id_kredit);
						if ($this->no_kredit->Exportable) $Doc->ExportField($this->no_kredit);
						if ($this->tgl_kredit->Exportable) $Doc->ExportField($this->tgl_kredit);
						if ($this->emp_id_auto->Exportable) $Doc->ExportField($this->emp_id_auto);
						if ($this->krd_id->Exportable) $Doc->ExportField($this->krd_id);
						if ($this->cara_hitung->Exportable) $Doc->ExportField($this->cara_hitung);
						if ($this->tot_kredit->Exportable) $Doc->ExportField($this->tot_kredit);
						if ($this->saldo_aw->Exportable) $Doc->ExportField($this->saldo_aw);
						if ($this->suku_bunga->Exportable) $Doc->ExportField($this->suku_bunga);
						if ($this->periode_bulan->Exportable) $Doc->ExportField($this->periode_bulan);
						if ($this->angs_pokok->Exportable) $Doc->ExportField($this->angs_pokok);
						if ($this->angs_pertama->Exportable) $Doc->ExportField($this->angs_pertama);
						if ($this->tot_debet->Exportable) $Doc->ExportField($this->tot_debet);
						if ($this->tot_angs_pokok->Exportable) $Doc->ExportField($this->tot_angs_pokok);
						if ($this->tot_bunga->Exportable) $Doc->ExportField($this->tot_bunga);
						if ($this->def_pembulatan->Exportable) $Doc->ExportField($this->def_pembulatan);
						if ($this->jumlah_piutang->Exportable) $Doc->ExportField($this->jumlah_piutang);
						if ($this->approv_by->Exportable) $Doc->ExportField($this->approv_by);
						if ($this->keterangan->Exportable) $Doc->ExportField($this->keterangan);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->status_lunas->Exportable) $Doc->ExportField($this->status_lunas);
						if ($this->lastupdate_date->Exportable) $Doc->ExportField($this->lastupdate_date);
						if ($this->lastupdate_user->Exportable) $Doc->ExportField($this->lastupdate_user);
					} else {
						if ($this->id_kredit->Exportable) $Doc->ExportField($this->id_kredit);
						if ($this->no_kredit->Exportable) $Doc->ExportField($this->no_kredit);
						if ($this->tgl_kredit->Exportable) $Doc->ExportField($this->tgl_kredit);
						if ($this->emp_id_auto->Exportable) $Doc->ExportField($this->emp_id_auto);
						if ($this->krd_id->Exportable) $Doc->ExportField($this->krd_id);
						if ($this->cara_hitung->Exportable) $Doc->ExportField($this->cara_hitung);
						if ($this->tot_kredit->Exportable) $Doc->ExportField($this->tot_kredit);
						if ($this->saldo_aw->Exportable) $Doc->ExportField($this->saldo_aw);
						if ($this->suku_bunga->Exportable) $Doc->ExportField($this->suku_bunga);
						if ($this->periode_bulan->Exportable) $Doc->ExportField($this->periode_bulan);
						if ($this->angs_pokok->Exportable) $Doc->ExportField($this->angs_pokok);
						if ($this->angs_pertama->Exportable) $Doc->ExportField($this->angs_pertama);
						if ($this->tot_debet->Exportable) $Doc->ExportField($this->tot_debet);
						if ($this->tot_angs_pokok->Exportable) $Doc->ExportField($this->tot_angs_pokok);
						if ($this->tot_bunga->Exportable) $Doc->ExportField($this->tot_bunga);
						if ($this->def_pembulatan->Exportable) $Doc->ExportField($this->def_pembulatan);
						if ($this->jumlah_piutang->Exportable) $Doc->ExportField($this->jumlah_piutang);
						if ($this->approv_by->Exportable) $Doc->ExportField($this->approv_by);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->status_lunas->Exportable) $Doc->ExportField($this->status_lunas);
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
