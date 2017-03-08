<?php

// Global variable for table object
$shift_result = NULL;

//
// Table class for shift_result
//
class cshift_result extends cTable {
	var $pegawai_id;
	var $tgl_shift;
	var $khusus_lembur;
	var $khusus_extra;
	var $temp_id_auto;
	var $jdw_kerja_m_id;
	var $jk_id;
	var $jns_dok;
	var $izin_jenis_id;
	var $cuti_n_id;
	var $libur_umum;
	var $libur_rutin;
	var $jk_ot;
	var $scan_in;
	var $att_id_in;
	var $late_permission;
	var $late_minute;
	var $late;
	var $break_out;
	var $att_id_break1;
	var $break_in;
	var $att_id_break2;
	var $break_minute;
	var $break;
	var $break_ot_minute;
	var $break_ot;
	var $early_permission;
	var $early_minute;
	var $early;
	var $scan_out;
	var $att_id_out;
	var $durasi_minute;
	var $durasi;
	var $durasi_eot_minute;
	var $jk_count_as;
	var $status_jk;
	var $keterangan;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'shift_result';
		$this->TableName = 'shift_result';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`shift_result`";
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
		$this->pegawai_id = new cField('shift_result', 'shift_result', 'x_pegawai_id', 'pegawai_id', '`pegawai_id`', '`pegawai_id`', 3, -1, FALSE, '`pegawai_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pegawai_id->Sortable = TRUE; // Allow sort
		$this->pegawai_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['pegawai_id'] = &$this->pegawai_id;

		// tgl_shift
		$this->tgl_shift = new cField('shift_result', 'shift_result', 'x_tgl_shift', 'tgl_shift', '`tgl_shift`', ew_CastDateFieldForLike('`tgl_shift`', 0, "DB"), 133, 0, FALSE, '`tgl_shift`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tgl_shift->Sortable = TRUE; // Allow sort
		$this->tgl_shift->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['tgl_shift'] = &$this->tgl_shift;

		// khusus_lembur
		$this->khusus_lembur = new cField('shift_result', 'shift_result', 'x_khusus_lembur', 'khusus_lembur', '`khusus_lembur`', '`khusus_lembur`', 16, -1, FALSE, '`khusus_lembur`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->khusus_lembur->Sortable = TRUE; // Allow sort
		$this->khusus_lembur->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['khusus_lembur'] = &$this->khusus_lembur;

		// khusus_extra
		$this->khusus_extra = new cField('shift_result', 'shift_result', 'x_khusus_extra', 'khusus_extra', '`khusus_extra`', '`khusus_extra`', 16, -1, FALSE, '`khusus_extra`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->khusus_extra->Sortable = TRUE; // Allow sort
		$this->khusus_extra->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['khusus_extra'] = &$this->khusus_extra;

		// temp_id_auto
		$this->temp_id_auto = new cField('shift_result', 'shift_result', 'x_temp_id_auto', 'temp_id_auto', '`temp_id_auto`', '`temp_id_auto`', 3, -1, FALSE, '`temp_id_auto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->temp_id_auto->Sortable = TRUE; // Allow sort
		$this->temp_id_auto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['temp_id_auto'] = &$this->temp_id_auto;

		// jdw_kerja_m_id
		$this->jdw_kerja_m_id = new cField('shift_result', 'shift_result', 'x_jdw_kerja_m_id', 'jdw_kerja_m_id', '`jdw_kerja_m_id`', '`jdw_kerja_m_id`', 3, -1, FALSE, '`jdw_kerja_m_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jdw_kerja_m_id->Sortable = TRUE; // Allow sort
		$this->jdw_kerja_m_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['jdw_kerja_m_id'] = &$this->jdw_kerja_m_id;

		// jk_id
		$this->jk_id = new cField('shift_result', 'shift_result', 'x_jk_id', 'jk_id', '`jk_id`', '`jk_id`', 3, -1, FALSE, '`jk_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jk_id->Sortable = TRUE; // Allow sort
		$this->jk_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['jk_id'] = &$this->jk_id;

		// jns_dok
		$this->jns_dok = new cField('shift_result', 'shift_result', 'x_jns_dok', 'jns_dok', '`jns_dok`', '`jns_dok`', 16, -1, FALSE, '`jns_dok`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jns_dok->Sortable = TRUE; // Allow sort
		$this->jns_dok->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['jns_dok'] = &$this->jns_dok;

		// izin_jenis_id
		$this->izin_jenis_id = new cField('shift_result', 'shift_result', 'x_izin_jenis_id', 'izin_jenis_id', '`izin_jenis_id`', '`izin_jenis_id`', 2, -1, FALSE, '`izin_jenis_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->izin_jenis_id->Sortable = TRUE; // Allow sort
		$this->izin_jenis_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['izin_jenis_id'] = &$this->izin_jenis_id;

		// cuti_n_id
		$this->cuti_n_id = new cField('shift_result', 'shift_result', 'x_cuti_n_id', 'cuti_n_id', '`cuti_n_id`', '`cuti_n_id`', 3, -1, FALSE, '`cuti_n_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->cuti_n_id->Sortable = TRUE; // Allow sort
		$this->cuti_n_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cuti_n_id'] = &$this->cuti_n_id;

		// libur_umum
		$this->libur_umum = new cField('shift_result', 'shift_result', 'x_libur_umum', 'libur_umum', '`libur_umum`', '`libur_umum`', 16, -1, FALSE, '`libur_umum`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->libur_umum->Sortable = TRUE; // Allow sort
		$this->libur_umum->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['libur_umum'] = &$this->libur_umum;

		// libur_rutin
		$this->libur_rutin = new cField('shift_result', 'shift_result', 'x_libur_rutin', 'libur_rutin', '`libur_rutin`', '`libur_rutin`', 16, -1, FALSE, '`libur_rutin`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->libur_rutin->Sortable = TRUE; // Allow sort
		$this->libur_rutin->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['libur_rutin'] = &$this->libur_rutin;

		// jk_ot
		$this->jk_ot = new cField('shift_result', 'shift_result', 'x_jk_ot', 'jk_ot', '`jk_ot`', '`jk_ot`', 16, -1, FALSE, '`jk_ot`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jk_ot->Sortable = TRUE; // Allow sort
		$this->jk_ot->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['jk_ot'] = &$this->jk_ot;

		// scan_in
		$this->scan_in = new cField('shift_result', 'shift_result', 'x_scan_in', 'scan_in', '`scan_in`', ew_CastDateFieldForLike('`scan_in`', 0, "DB"), 135, 0, FALSE, '`scan_in`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->scan_in->Sortable = TRUE; // Allow sort
		$this->scan_in->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['scan_in'] = &$this->scan_in;

		// att_id_in
		$this->att_id_in = new cField('shift_result', 'shift_result', 'x_att_id_in', 'att_id_in', '`att_id_in`', '`att_id_in`', 200, -1, FALSE, '`att_id_in`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->att_id_in->Sortable = TRUE; // Allow sort
		$this->fields['att_id_in'] = &$this->att_id_in;

		// late_permission
		$this->late_permission = new cField('shift_result', 'shift_result', 'x_late_permission', 'late_permission', '`late_permission`', '`late_permission`', 16, -1, FALSE, '`late_permission`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->late_permission->Sortable = TRUE; // Allow sort
		$this->late_permission->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['late_permission'] = &$this->late_permission;

		// late_minute
		$this->late_minute = new cField('shift_result', 'shift_result', 'x_late_minute', 'late_minute', '`late_minute`', '`late_minute`', 2, -1, FALSE, '`late_minute`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->late_minute->Sortable = TRUE; // Allow sort
		$this->late_minute->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['late_minute'] = &$this->late_minute;

		// late
		$this->late = new cField('shift_result', 'shift_result', 'x_late', 'late', '`late`', '`late`', 4, -1, FALSE, '`late`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->late->Sortable = TRUE; // Allow sort
		$this->late->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['late'] = &$this->late;

		// break_out
		$this->break_out = new cField('shift_result', 'shift_result', 'x_break_out', 'break_out', '`break_out`', ew_CastDateFieldForLike('`break_out`', 0, "DB"), 135, 0, FALSE, '`break_out`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->break_out->Sortable = TRUE; // Allow sort
		$this->break_out->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['break_out'] = &$this->break_out;

		// att_id_break1
		$this->att_id_break1 = new cField('shift_result', 'shift_result', 'x_att_id_break1', 'att_id_break1', '`att_id_break1`', '`att_id_break1`', 200, -1, FALSE, '`att_id_break1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->att_id_break1->Sortable = TRUE; // Allow sort
		$this->fields['att_id_break1'] = &$this->att_id_break1;

		// break_in
		$this->break_in = new cField('shift_result', 'shift_result', 'x_break_in', 'break_in', '`break_in`', ew_CastDateFieldForLike('`break_in`', 0, "DB"), 135, 0, FALSE, '`break_in`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->break_in->Sortable = TRUE; // Allow sort
		$this->break_in->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['break_in'] = &$this->break_in;

		// att_id_break2
		$this->att_id_break2 = new cField('shift_result', 'shift_result', 'x_att_id_break2', 'att_id_break2', '`att_id_break2`', '`att_id_break2`', 200, -1, FALSE, '`att_id_break2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->att_id_break2->Sortable = TRUE; // Allow sort
		$this->fields['att_id_break2'] = &$this->att_id_break2;

		// break_minute
		$this->break_minute = new cField('shift_result', 'shift_result', 'x_break_minute', 'break_minute', '`break_minute`', '`break_minute`', 2, -1, FALSE, '`break_minute`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->break_minute->Sortable = TRUE; // Allow sort
		$this->break_minute->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['break_minute'] = &$this->break_minute;

		// break
		$this->break = new cField('shift_result', 'shift_result', 'x_break', 'break', '`break`', '`break`', 4, -1, FALSE, '`break`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->break->Sortable = TRUE; // Allow sort
		$this->break->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['break'] = &$this->break;

		// break_ot_minute
		$this->break_ot_minute = new cField('shift_result', 'shift_result', 'x_break_ot_minute', 'break_ot_minute', '`break_ot_minute`', '`break_ot_minute`', 2, -1, FALSE, '`break_ot_minute`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->break_ot_minute->Sortable = TRUE; // Allow sort
		$this->break_ot_minute->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['break_ot_minute'] = &$this->break_ot_minute;

		// break_ot
		$this->break_ot = new cField('shift_result', 'shift_result', 'x_break_ot', 'break_ot', '`break_ot`', '`break_ot`', 4, -1, FALSE, '`break_ot`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->break_ot->Sortable = TRUE; // Allow sort
		$this->break_ot->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['break_ot'] = &$this->break_ot;

		// early_permission
		$this->early_permission = new cField('shift_result', 'shift_result', 'x_early_permission', 'early_permission', '`early_permission`', '`early_permission`', 16, -1, FALSE, '`early_permission`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->early_permission->Sortable = TRUE; // Allow sort
		$this->early_permission->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['early_permission'] = &$this->early_permission;

		// early_minute
		$this->early_minute = new cField('shift_result', 'shift_result', 'x_early_minute', 'early_minute', '`early_minute`', '`early_minute`', 2, -1, FALSE, '`early_minute`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->early_minute->Sortable = TRUE; // Allow sort
		$this->early_minute->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['early_minute'] = &$this->early_minute;

		// early
		$this->early = new cField('shift_result', 'shift_result', 'x_early', 'early', '`early`', '`early`', 4, -1, FALSE, '`early`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->early->Sortable = TRUE; // Allow sort
		$this->early->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['early'] = &$this->early;

		// scan_out
		$this->scan_out = new cField('shift_result', 'shift_result', 'x_scan_out', 'scan_out', '`scan_out`', ew_CastDateFieldForLike('`scan_out`', 0, "DB"), 135, 0, FALSE, '`scan_out`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->scan_out->Sortable = TRUE; // Allow sort
		$this->scan_out->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['scan_out'] = &$this->scan_out;

		// att_id_out
		$this->att_id_out = new cField('shift_result', 'shift_result', 'x_att_id_out', 'att_id_out', '`att_id_out`', '`att_id_out`', 200, -1, FALSE, '`att_id_out`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->att_id_out->Sortable = TRUE; // Allow sort
		$this->fields['att_id_out'] = &$this->att_id_out;

		// durasi_minute
		$this->durasi_minute = new cField('shift_result', 'shift_result', 'x_durasi_minute', 'durasi_minute', '`durasi_minute`', '`durasi_minute`', 2, -1, FALSE, '`durasi_minute`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->durasi_minute->Sortable = TRUE; // Allow sort
		$this->durasi_minute->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['durasi_minute'] = &$this->durasi_minute;

		// durasi
		$this->durasi = new cField('shift_result', 'shift_result', 'x_durasi', 'durasi', '`durasi`', '`durasi`', 4, -1, FALSE, '`durasi`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->durasi->Sortable = TRUE; // Allow sort
		$this->durasi->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['durasi'] = &$this->durasi;

		// durasi_eot_minute
		$this->durasi_eot_minute = new cField('shift_result', 'shift_result', 'x_durasi_eot_minute', 'durasi_eot_minute', '`durasi_eot_minute`', '`durasi_eot_minute`', 2, -1, FALSE, '`durasi_eot_minute`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->durasi_eot_minute->Sortable = TRUE; // Allow sort
		$this->durasi_eot_minute->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['durasi_eot_minute'] = &$this->durasi_eot_minute;

		// jk_count_as
		$this->jk_count_as = new cField('shift_result', 'shift_result', 'x_jk_count_as', 'jk_count_as', '`jk_count_as`', '`jk_count_as`', 4, -1, FALSE, '`jk_count_as`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jk_count_as->Sortable = TRUE; // Allow sort
		$this->jk_count_as->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['jk_count_as'] = &$this->jk_count_as;

		// status_jk
		$this->status_jk = new cField('shift_result', 'shift_result', 'x_status_jk', 'status_jk', '`status_jk`', '`status_jk`', 16, -1, FALSE, '`status_jk`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->status_jk->Sortable = TRUE; // Allow sort
		$this->status_jk->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['status_jk'] = &$this->status_jk;

		// keterangan
		$this->keterangan = new cField('shift_result', 'shift_result', 'x_keterangan', 'keterangan', '`keterangan`', '`keterangan`', 201, -1, FALSE, '`keterangan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`shift_result`";
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
			if (array_key_exists('tgl_shift', $rs))
				ew_AddFilter($where, ew_QuotedName('tgl_shift', $this->DBID) . '=' . ew_QuotedValue($rs['tgl_shift'], $this->tgl_shift->FldDataType, $this->DBID));
			if (array_key_exists('khusus_lembur', $rs))
				ew_AddFilter($where, ew_QuotedName('khusus_lembur', $this->DBID) . '=' . ew_QuotedValue($rs['khusus_lembur'], $this->khusus_lembur->FldDataType, $this->DBID));
			if (array_key_exists('khusus_extra', $rs))
				ew_AddFilter($where, ew_QuotedName('khusus_extra', $this->DBID) . '=' . ew_QuotedValue($rs['khusus_extra'], $this->khusus_extra->FldDataType, $this->DBID));
			if (array_key_exists('temp_id_auto', $rs))
				ew_AddFilter($where, ew_QuotedName('temp_id_auto', $this->DBID) . '=' . ew_QuotedValue($rs['temp_id_auto'], $this->temp_id_auto->FldDataType, $this->DBID));
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
		return "`pegawai_id` = @pegawai_id@ AND `tgl_shift` = '@tgl_shift@' AND `khusus_lembur` = @khusus_lembur@ AND `khusus_extra` = @khusus_extra@ AND `temp_id_auto` = @temp_id_auto@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->pegawai_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@pegawai_id@", ew_AdjustSql($this->pegawai_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		$sKeyFilter = str_replace("@tgl_shift@", ew_AdjustSql(ew_UnFormatDateTime($this->tgl_shift->CurrentValue,0), $this->DBID), $sKeyFilter); // Replace key value
		if (!is_numeric($this->khusus_lembur->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@khusus_lembur@", ew_AdjustSql($this->khusus_lembur->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		if (!is_numeric($this->khusus_extra->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@khusus_extra@", ew_AdjustSql($this->khusus_extra->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		if (!is_numeric($this->temp_id_auto->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@temp_id_auto@", ew_AdjustSql($this->temp_id_auto->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "shift_resultlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "shift_resultlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("shift_resultview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("shift_resultview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "shift_resultadd.php?" . $this->UrlParm($parm);
		else
			$url = "shift_resultadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("shift_resultedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("shift_resultadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("shift_resultdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "pegawai_id:" . ew_VarToJson($this->pegawai_id->CurrentValue, "number", "'");
		$json .= ",tgl_shift:" . ew_VarToJson($this->tgl_shift->CurrentValue, "string", "'");
		$json .= ",khusus_lembur:" . ew_VarToJson($this->khusus_lembur->CurrentValue, "number", "'");
		$json .= ",khusus_extra:" . ew_VarToJson($this->khusus_extra->CurrentValue, "number", "'");
		$json .= ",temp_id_auto:" . ew_VarToJson($this->temp_id_auto->CurrentValue, "number", "'");
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
		if (!is_null($this->tgl_shift->CurrentValue)) {
			$sUrl .= "&tgl_shift=" . urlencode($this->tgl_shift->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->khusus_lembur->CurrentValue)) {
			$sUrl .= "&khusus_lembur=" . urlencode($this->khusus_lembur->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->khusus_extra->CurrentValue)) {
			$sUrl .= "&khusus_extra=" . urlencode($this->khusus_extra->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->temp_id_auto->CurrentValue)) {
			$sUrl .= "&temp_id_auto=" . urlencode($this->temp_id_auto->CurrentValue);
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
			if ($isPost && isset($_POST["tgl_shift"]))
				$arKey[] = ew_StripSlashes($_POST["tgl_shift"]);
			elseif (isset($_GET["tgl_shift"]))
				$arKey[] = ew_StripSlashes($_GET["tgl_shift"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["khusus_lembur"]))
				$arKey[] = ew_StripSlashes($_POST["khusus_lembur"]);
			elseif (isset($_GET["khusus_lembur"]))
				$arKey[] = ew_StripSlashes($_GET["khusus_lembur"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["khusus_extra"]))
				$arKey[] = ew_StripSlashes($_POST["khusus_extra"]);
			elseif (isset($_GET["khusus_extra"]))
				$arKey[] = ew_StripSlashes($_GET["khusus_extra"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["temp_id_auto"]))
				$arKey[] = ew_StripSlashes($_POST["temp_id_auto"]);
			elseif (isset($_GET["temp_id_auto"]))
				$arKey[] = ew_StripSlashes($_GET["temp_id_auto"]);
			else
				$arKeys = NULL; // Do not setup
			if (is_array($arKeys)) $arKeys[] = $arKey;

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_array($key) || count($key) <> 5)
					continue; // Just skip so other keys will still work
				if (!is_numeric($key[0])) // pegawai_id
					continue;
				if (!is_numeric($key[2])) // khusus_lembur
					continue;
				if (!is_numeric($key[3])) // khusus_extra
					continue;
				if (!is_numeric($key[4])) // temp_id_auto
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
			$this->tgl_shift->CurrentValue = $key[1];
			$this->khusus_lembur->CurrentValue = $key[2];
			$this->khusus_extra->CurrentValue = $key[3];
			$this->temp_id_auto->CurrentValue = $key[4];
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
		$this->tgl_shift->setDbValue($rs->fields('tgl_shift'));
		$this->khusus_lembur->setDbValue($rs->fields('khusus_lembur'));
		$this->khusus_extra->setDbValue($rs->fields('khusus_extra'));
		$this->temp_id_auto->setDbValue($rs->fields('temp_id_auto'));
		$this->jdw_kerja_m_id->setDbValue($rs->fields('jdw_kerja_m_id'));
		$this->jk_id->setDbValue($rs->fields('jk_id'));
		$this->jns_dok->setDbValue($rs->fields('jns_dok'));
		$this->izin_jenis_id->setDbValue($rs->fields('izin_jenis_id'));
		$this->cuti_n_id->setDbValue($rs->fields('cuti_n_id'));
		$this->libur_umum->setDbValue($rs->fields('libur_umum'));
		$this->libur_rutin->setDbValue($rs->fields('libur_rutin'));
		$this->jk_ot->setDbValue($rs->fields('jk_ot'));
		$this->scan_in->setDbValue($rs->fields('scan_in'));
		$this->att_id_in->setDbValue($rs->fields('att_id_in'));
		$this->late_permission->setDbValue($rs->fields('late_permission'));
		$this->late_minute->setDbValue($rs->fields('late_minute'));
		$this->late->setDbValue($rs->fields('late'));
		$this->break_out->setDbValue($rs->fields('break_out'));
		$this->att_id_break1->setDbValue($rs->fields('att_id_break1'));
		$this->break_in->setDbValue($rs->fields('break_in'));
		$this->att_id_break2->setDbValue($rs->fields('att_id_break2'));
		$this->break_minute->setDbValue($rs->fields('break_minute'));
		$this->break->setDbValue($rs->fields('break'));
		$this->break_ot_minute->setDbValue($rs->fields('break_ot_minute'));
		$this->break_ot->setDbValue($rs->fields('break_ot'));
		$this->early_permission->setDbValue($rs->fields('early_permission'));
		$this->early_minute->setDbValue($rs->fields('early_minute'));
		$this->early->setDbValue($rs->fields('early'));
		$this->scan_out->setDbValue($rs->fields('scan_out'));
		$this->att_id_out->setDbValue($rs->fields('att_id_out'));
		$this->durasi_minute->setDbValue($rs->fields('durasi_minute'));
		$this->durasi->setDbValue($rs->fields('durasi'));
		$this->durasi_eot_minute->setDbValue($rs->fields('durasi_eot_minute'));
		$this->jk_count_as->setDbValue($rs->fields('jk_count_as'));
		$this->status_jk->setDbValue($rs->fields('status_jk'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// pegawai_id
		// tgl_shift
		// khusus_lembur
		// khusus_extra
		// temp_id_auto
		// jdw_kerja_m_id
		// jk_id
		// jns_dok
		// izin_jenis_id
		// cuti_n_id
		// libur_umum
		// libur_rutin
		// jk_ot
		// scan_in
		// att_id_in
		// late_permission
		// late_minute
		// late
		// break_out
		// att_id_break1
		// break_in
		// att_id_break2
		// break_minute
		// break
		// break_ot_minute
		// break_ot
		// early_permission
		// early_minute
		// early
		// scan_out
		// att_id_out
		// durasi_minute
		// durasi
		// durasi_eot_minute
		// jk_count_as
		// status_jk
		// keterangan
		// pegawai_id

		$this->pegawai_id->ViewValue = $this->pegawai_id->CurrentValue;
		$this->pegawai_id->ViewCustomAttributes = "";

		// tgl_shift
		$this->tgl_shift->ViewValue = $this->tgl_shift->CurrentValue;
		$this->tgl_shift->ViewValue = ew_FormatDateTime($this->tgl_shift->ViewValue, 0);
		$this->tgl_shift->ViewCustomAttributes = "";

		// khusus_lembur
		$this->khusus_lembur->ViewValue = $this->khusus_lembur->CurrentValue;
		$this->khusus_lembur->ViewCustomAttributes = "";

		// khusus_extra
		$this->khusus_extra->ViewValue = $this->khusus_extra->CurrentValue;
		$this->khusus_extra->ViewCustomAttributes = "";

		// temp_id_auto
		$this->temp_id_auto->ViewValue = $this->temp_id_auto->CurrentValue;
		$this->temp_id_auto->ViewCustomAttributes = "";

		// jdw_kerja_m_id
		$this->jdw_kerja_m_id->ViewValue = $this->jdw_kerja_m_id->CurrentValue;
		$this->jdw_kerja_m_id->ViewCustomAttributes = "";

		// jk_id
		$this->jk_id->ViewValue = $this->jk_id->CurrentValue;
		$this->jk_id->ViewCustomAttributes = "";

		// jns_dok
		$this->jns_dok->ViewValue = $this->jns_dok->CurrentValue;
		$this->jns_dok->ViewCustomAttributes = "";

		// izin_jenis_id
		$this->izin_jenis_id->ViewValue = $this->izin_jenis_id->CurrentValue;
		$this->izin_jenis_id->ViewCustomAttributes = "";

		// cuti_n_id
		$this->cuti_n_id->ViewValue = $this->cuti_n_id->CurrentValue;
		$this->cuti_n_id->ViewCustomAttributes = "";

		// libur_umum
		$this->libur_umum->ViewValue = $this->libur_umum->CurrentValue;
		$this->libur_umum->ViewCustomAttributes = "";

		// libur_rutin
		$this->libur_rutin->ViewValue = $this->libur_rutin->CurrentValue;
		$this->libur_rutin->ViewCustomAttributes = "";

		// jk_ot
		$this->jk_ot->ViewValue = $this->jk_ot->CurrentValue;
		$this->jk_ot->ViewCustomAttributes = "";

		// scan_in
		$this->scan_in->ViewValue = $this->scan_in->CurrentValue;
		$this->scan_in->ViewValue = ew_FormatDateTime($this->scan_in->ViewValue, 0);
		$this->scan_in->ViewCustomAttributes = "";

		// att_id_in
		$this->att_id_in->ViewValue = $this->att_id_in->CurrentValue;
		$this->att_id_in->ViewCustomAttributes = "";

		// late_permission
		$this->late_permission->ViewValue = $this->late_permission->CurrentValue;
		$this->late_permission->ViewCustomAttributes = "";

		// late_minute
		$this->late_minute->ViewValue = $this->late_minute->CurrentValue;
		$this->late_minute->ViewCustomAttributes = "";

		// late
		$this->late->ViewValue = $this->late->CurrentValue;
		$this->late->ViewCustomAttributes = "";

		// break_out
		$this->break_out->ViewValue = $this->break_out->CurrentValue;
		$this->break_out->ViewValue = ew_FormatDateTime($this->break_out->ViewValue, 0);
		$this->break_out->ViewCustomAttributes = "";

		// att_id_break1
		$this->att_id_break1->ViewValue = $this->att_id_break1->CurrentValue;
		$this->att_id_break1->ViewCustomAttributes = "";

		// break_in
		$this->break_in->ViewValue = $this->break_in->CurrentValue;
		$this->break_in->ViewValue = ew_FormatDateTime($this->break_in->ViewValue, 0);
		$this->break_in->ViewCustomAttributes = "";

		// att_id_break2
		$this->att_id_break2->ViewValue = $this->att_id_break2->CurrentValue;
		$this->att_id_break2->ViewCustomAttributes = "";

		// break_minute
		$this->break_minute->ViewValue = $this->break_minute->CurrentValue;
		$this->break_minute->ViewCustomAttributes = "";

		// break
		$this->break->ViewValue = $this->break->CurrentValue;
		$this->break->ViewCustomAttributes = "";

		// break_ot_minute
		$this->break_ot_minute->ViewValue = $this->break_ot_minute->CurrentValue;
		$this->break_ot_minute->ViewCustomAttributes = "";

		// break_ot
		$this->break_ot->ViewValue = $this->break_ot->CurrentValue;
		$this->break_ot->ViewCustomAttributes = "";

		// early_permission
		$this->early_permission->ViewValue = $this->early_permission->CurrentValue;
		$this->early_permission->ViewCustomAttributes = "";

		// early_minute
		$this->early_minute->ViewValue = $this->early_minute->CurrentValue;
		$this->early_minute->ViewCustomAttributes = "";

		// early
		$this->early->ViewValue = $this->early->CurrentValue;
		$this->early->ViewCustomAttributes = "";

		// scan_out
		$this->scan_out->ViewValue = $this->scan_out->CurrentValue;
		$this->scan_out->ViewValue = ew_FormatDateTime($this->scan_out->ViewValue, 0);
		$this->scan_out->ViewCustomAttributes = "";

		// att_id_out
		$this->att_id_out->ViewValue = $this->att_id_out->CurrentValue;
		$this->att_id_out->ViewCustomAttributes = "";

		// durasi_minute
		$this->durasi_minute->ViewValue = $this->durasi_minute->CurrentValue;
		$this->durasi_minute->ViewCustomAttributes = "";

		// durasi
		$this->durasi->ViewValue = $this->durasi->CurrentValue;
		$this->durasi->ViewCustomAttributes = "";

		// durasi_eot_minute
		$this->durasi_eot_minute->ViewValue = $this->durasi_eot_minute->CurrentValue;
		$this->durasi_eot_minute->ViewCustomAttributes = "";

		// jk_count_as
		$this->jk_count_as->ViewValue = $this->jk_count_as->CurrentValue;
		$this->jk_count_as->ViewCustomAttributes = "";

		// status_jk
		$this->status_jk->ViewValue = $this->status_jk->CurrentValue;
		$this->status_jk->ViewCustomAttributes = "";

		// keterangan
		$this->keterangan->ViewValue = $this->keterangan->CurrentValue;
		$this->keterangan->ViewCustomAttributes = "";

		// pegawai_id
		$this->pegawai_id->LinkCustomAttributes = "";
		$this->pegawai_id->HrefValue = "";
		$this->pegawai_id->TooltipValue = "";

		// tgl_shift
		$this->tgl_shift->LinkCustomAttributes = "";
		$this->tgl_shift->HrefValue = "";
		$this->tgl_shift->TooltipValue = "";

		// khusus_lembur
		$this->khusus_lembur->LinkCustomAttributes = "";
		$this->khusus_lembur->HrefValue = "";
		$this->khusus_lembur->TooltipValue = "";

		// khusus_extra
		$this->khusus_extra->LinkCustomAttributes = "";
		$this->khusus_extra->HrefValue = "";
		$this->khusus_extra->TooltipValue = "";

		// temp_id_auto
		$this->temp_id_auto->LinkCustomAttributes = "";
		$this->temp_id_auto->HrefValue = "";
		$this->temp_id_auto->TooltipValue = "";

		// jdw_kerja_m_id
		$this->jdw_kerja_m_id->LinkCustomAttributes = "";
		$this->jdw_kerja_m_id->HrefValue = "";
		$this->jdw_kerja_m_id->TooltipValue = "";

		// jk_id
		$this->jk_id->LinkCustomAttributes = "";
		$this->jk_id->HrefValue = "";
		$this->jk_id->TooltipValue = "";

		// jns_dok
		$this->jns_dok->LinkCustomAttributes = "";
		$this->jns_dok->HrefValue = "";
		$this->jns_dok->TooltipValue = "";

		// izin_jenis_id
		$this->izin_jenis_id->LinkCustomAttributes = "";
		$this->izin_jenis_id->HrefValue = "";
		$this->izin_jenis_id->TooltipValue = "";

		// cuti_n_id
		$this->cuti_n_id->LinkCustomAttributes = "";
		$this->cuti_n_id->HrefValue = "";
		$this->cuti_n_id->TooltipValue = "";

		// libur_umum
		$this->libur_umum->LinkCustomAttributes = "";
		$this->libur_umum->HrefValue = "";
		$this->libur_umum->TooltipValue = "";

		// libur_rutin
		$this->libur_rutin->LinkCustomAttributes = "";
		$this->libur_rutin->HrefValue = "";
		$this->libur_rutin->TooltipValue = "";

		// jk_ot
		$this->jk_ot->LinkCustomAttributes = "";
		$this->jk_ot->HrefValue = "";
		$this->jk_ot->TooltipValue = "";

		// scan_in
		$this->scan_in->LinkCustomAttributes = "";
		$this->scan_in->HrefValue = "";
		$this->scan_in->TooltipValue = "";

		// att_id_in
		$this->att_id_in->LinkCustomAttributes = "";
		$this->att_id_in->HrefValue = "";
		$this->att_id_in->TooltipValue = "";

		// late_permission
		$this->late_permission->LinkCustomAttributes = "";
		$this->late_permission->HrefValue = "";
		$this->late_permission->TooltipValue = "";

		// late_minute
		$this->late_minute->LinkCustomAttributes = "";
		$this->late_minute->HrefValue = "";
		$this->late_minute->TooltipValue = "";

		// late
		$this->late->LinkCustomAttributes = "";
		$this->late->HrefValue = "";
		$this->late->TooltipValue = "";

		// break_out
		$this->break_out->LinkCustomAttributes = "";
		$this->break_out->HrefValue = "";
		$this->break_out->TooltipValue = "";

		// att_id_break1
		$this->att_id_break1->LinkCustomAttributes = "";
		$this->att_id_break1->HrefValue = "";
		$this->att_id_break1->TooltipValue = "";

		// break_in
		$this->break_in->LinkCustomAttributes = "";
		$this->break_in->HrefValue = "";
		$this->break_in->TooltipValue = "";

		// att_id_break2
		$this->att_id_break2->LinkCustomAttributes = "";
		$this->att_id_break2->HrefValue = "";
		$this->att_id_break2->TooltipValue = "";

		// break_minute
		$this->break_minute->LinkCustomAttributes = "";
		$this->break_minute->HrefValue = "";
		$this->break_minute->TooltipValue = "";

		// break
		$this->break->LinkCustomAttributes = "";
		$this->break->HrefValue = "";
		$this->break->TooltipValue = "";

		// break_ot_minute
		$this->break_ot_minute->LinkCustomAttributes = "";
		$this->break_ot_minute->HrefValue = "";
		$this->break_ot_minute->TooltipValue = "";

		// break_ot
		$this->break_ot->LinkCustomAttributes = "";
		$this->break_ot->HrefValue = "";
		$this->break_ot->TooltipValue = "";

		// early_permission
		$this->early_permission->LinkCustomAttributes = "";
		$this->early_permission->HrefValue = "";
		$this->early_permission->TooltipValue = "";

		// early_minute
		$this->early_minute->LinkCustomAttributes = "";
		$this->early_minute->HrefValue = "";
		$this->early_minute->TooltipValue = "";

		// early
		$this->early->LinkCustomAttributes = "";
		$this->early->HrefValue = "";
		$this->early->TooltipValue = "";

		// scan_out
		$this->scan_out->LinkCustomAttributes = "";
		$this->scan_out->HrefValue = "";
		$this->scan_out->TooltipValue = "";

		// att_id_out
		$this->att_id_out->LinkCustomAttributes = "";
		$this->att_id_out->HrefValue = "";
		$this->att_id_out->TooltipValue = "";

		// durasi_minute
		$this->durasi_minute->LinkCustomAttributes = "";
		$this->durasi_minute->HrefValue = "";
		$this->durasi_minute->TooltipValue = "";

		// durasi
		$this->durasi->LinkCustomAttributes = "";
		$this->durasi->HrefValue = "";
		$this->durasi->TooltipValue = "";

		// durasi_eot_minute
		$this->durasi_eot_minute->LinkCustomAttributes = "";
		$this->durasi_eot_minute->HrefValue = "";
		$this->durasi_eot_minute->TooltipValue = "";

		// jk_count_as
		$this->jk_count_as->LinkCustomAttributes = "";
		$this->jk_count_as->HrefValue = "";
		$this->jk_count_as->TooltipValue = "";

		// status_jk
		$this->status_jk->LinkCustomAttributes = "";
		$this->status_jk->HrefValue = "";
		$this->status_jk->TooltipValue = "";

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

		// pegawai_id
		$this->pegawai_id->EditAttrs["class"] = "form-control";
		$this->pegawai_id->EditCustomAttributes = "";
		$this->pegawai_id->EditValue = $this->pegawai_id->CurrentValue;
		$this->pegawai_id->ViewCustomAttributes = "";

		// tgl_shift
		$this->tgl_shift->EditAttrs["class"] = "form-control";
		$this->tgl_shift->EditCustomAttributes = "";
		$this->tgl_shift->EditValue = $this->tgl_shift->CurrentValue;
		$this->tgl_shift->EditValue = ew_FormatDateTime($this->tgl_shift->EditValue, 0);
		$this->tgl_shift->ViewCustomAttributes = "";

		// khusus_lembur
		$this->khusus_lembur->EditAttrs["class"] = "form-control";
		$this->khusus_lembur->EditCustomAttributes = "";
		$this->khusus_lembur->EditValue = $this->khusus_lembur->CurrentValue;
		$this->khusus_lembur->ViewCustomAttributes = "";

		// khusus_extra
		$this->khusus_extra->EditAttrs["class"] = "form-control";
		$this->khusus_extra->EditCustomAttributes = "";
		$this->khusus_extra->EditValue = $this->khusus_extra->CurrentValue;
		$this->khusus_extra->ViewCustomAttributes = "";

		// temp_id_auto
		$this->temp_id_auto->EditAttrs["class"] = "form-control";
		$this->temp_id_auto->EditCustomAttributes = "";
		$this->temp_id_auto->EditValue = $this->temp_id_auto->CurrentValue;
		$this->temp_id_auto->ViewCustomAttributes = "";

		// jdw_kerja_m_id
		$this->jdw_kerja_m_id->EditAttrs["class"] = "form-control";
		$this->jdw_kerja_m_id->EditCustomAttributes = "";
		$this->jdw_kerja_m_id->EditValue = $this->jdw_kerja_m_id->CurrentValue;
		$this->jdw_kerja_m_id->PlaceHolder = ew_RemoveHtml($this->jdw_kerja_m_id->FldCaption());

		// jk_id
		$this->jk_id->EditAttrs["class"] = "form-control";
		$this->jk_id->EditCustomAttributes = "";
		$this->jk_id->EditValue = $this->jk_id->CurrentValue;
		$this->jk_id->PlaceHolder = ew_RemoveHtml($this->jk_id->FldCaption());

		// jns_dok
		$this->jns_dok->EditAttrs["class"] = "form-control";
		$this->jns_dok->EditCustomAttributes = "";
		$this->jns_dok->EditValue = $this->jns_dok->CurrentValue;
		$this->jns_dok->PlaceHolder = ew_RemoveHtml($this->jns_dok->FldCaption());

		// izin_jenis_id
		$this->izin_jenis_id->EditAttrs["class"] = "form-control";
		$this->izin_jenis_id->EditCustomAttributes = "";
		$this->izin_jenis_id->EditValue = $this->izin_jenis_id->CurrentValue;
		$this->izin_jenis_id->PlaceHolder = ew_RemoveHtml($this->izin_jenis_id->FldCaption());

		// cuti_n_id
		$this->cuti_n_id->EditAttrs["class"] = "form-control";
		$this->cuti_n_id->EditCustomAttributes = "";
		$this->cuti_n_id->EditValue = $this->cuti_n_id->CurrentValue;
		$this->cuti_n_id->PlaceHolder = ew_RemoveHtml($this->cuti_n_id->FldCaption());

		// libur_umum
		$this->libur_umum->EditAttrs["class"] = "form-control";
		$this->libur_umum->EditCustomAttributes = "";
		$this->libur_umum->EditValue = $this->libur_umum->CurrentValue;
		$this->libur_umum->PlaceHolder = ew_RemoveHtml($this->libur_umum->FldCaption());

		// libur_rutin
		$this->libur_rutin->EditAttrs["class"] = "form-control";
		$this->libur_rutin->EditCustomAttributes = "";
		$this->libur_rutin->EditValue = $this->libur_rutin->CurrentValue;
		$this->libur_rutin->PlaceHolder = ew_RemoveHtml($this->libur_rutin->FldCaption());

		// jk_ot
		$this->jk_ot->EditAttrs["class"] = "form-control";
		$this->jk_ot->EditCustomAttributes = "";
		$this->jk_ot->EditValue = $this->jk_ot->CurrentValue;
		$this->jk_ot->PlaceHolder = ew_RemoveHtml($this->jk_ot->FldCaption());

		// scan_in
		$this->scan_in->EditAttrs["class"] = "form-control";
		$this->scan_in->EditCustomAttributes = "";
		$this->scan_in->EditValue = ew_FormatDateTime($this->scan_in->CurrentValue, 8);
		$this->scan_in->PlaceHolder = ew_RemoveHtml($this->scan_in->FldCaption());

		// att_id_in
		$this->att_id_in->EditAttrs["class"] = "form-control";
		$this->att_id_in->EditCustomAttributes = "";
		$this->att_id_in->EditValue = $this->att_id_in->CurrentValue;
		$this->att_id_in->PlaceHolder = ew_RemoveHtml($this->att_id_in->FldCaption());

		// late_permission
		$this->late_permission->EditAttrs["class"] = "form-control";
		$this->late_permission->EditCustomAttributes = "";
		$this->late_permission->EditValue = $this->late_permission->CurrentValue;
		$this->late_permission->PlaceHolder = ew_RemoveHtml($this->late_permission->FldCaption());

		// late_minute
		$this->late_minute->EditAttrs["class"] = "form-control";
		$this->late_minute->EditCustomAttributes = "";
		$this->late_minute->EditValue = $this->late_minute->CurrentValue;
		$this->late_minute->PlaceHolder = ew_RemoveHtml($this->late_minute->FldCaption());

		// late
		$this->late->EditAttrs["class"] = "form-control";
		$this->late->EditCustomAttributes = "";
		$this->late->EditValue = $this->late->CurrentValue;
		$this->late->PlaceHolder = ew_RemoveHtml($this->late->FldCaption());
		if (strval($this->late->EditValue) <> "" && is_numeric($this->late->EditValue)) $this->late->EditValue = ew_FormatNumber($this->late->EditValue, -2, -1, -2, 0);

		// break_out
		$this->break_out->EditAttrs["class"] = "form-control";
		$this->break_out->EditCustomAttributes = "";
		$this->break_out->EditValue = ew_FormatDateTime($this->break_out->CurrentValue, 8);
		$this->break_out->PlaceHolder = ew_RemoveHtml($this->break_out->FldCaption());

		// att_id_break1
		$this->att_id_break1->EditAttrs["class"] = "form-control";
		$this->att_id_break1->EditCustomAttributes = "";
		$this->att_id_break1->EditValue = $this->att_id_break1->CurrentValue;
		$this->att_id_break1->PlaceHolder = ew_RemoveHtml($this->att_id_break1->FldCaption());

		// break_in
		$this->break_in->EditAttrs["class"] = "form-control";
		$this->break_in->EditCustomAttributes = "";
		$this->break_in->EditValue = ew_FormatDateTime($this->break_in->CurrentValue, 8);
		$this->break_in->PlaceHolder = ew_RemoveHtml($this->break_in->FldCaption());

		// att_id_break2
		$this->att_id_break2->EditAttrs["class"] = "form-control";
		$this->att_id_break2->EditCustomAttributes = "";
		$this->att_id_break2->EditValue = $this->att_id_break2->CurrentValue;
		$this->att_id_break2->PlaceHolder = ew_RemoveHtml($this->att_id_break2->FldCaption());

		// break_minute
		$this->break_minute->EditAttrs["class"] = "form-control";
		$this->break_minute->EditCustomAttributes = "";
		$this->break_minute->EditValue = $this->break_minute->CurrentValue;
		$this->break_minute->PlaceHolder = ew_RemoveHtml($this->break_minute->FldCaption());

		// break
		$this->break->EditAttrs["class"] = "form-control";
		$this->break->EditCustomAttributes = "";
		$this->break->EditValue = $this->break->CurrentValue;
		$this->break->PlaceHolder = ew_RemoveHtml($this->break->FldCaption());
		if (strval($this->break->EditValue) <> "" && is_numeric($this->break->EditValue)) $this->break->EditValue = ew_FormatNumber($this->break->EditValue, -2, -1, -2, 0);

		// break_ot_minute
		$this->break_ot_minute->EditAttrs["class"] = "form-control";
		$this->break_ot_minute->EditCustomAttributes = "";
		$this->break_ot_minute->EditValue = $this->break_ot_minute->CurrentValue;
		$this->break_ot_minute->PlaceHolder = ew_RemoveHtml($this->break_ot_minute->FldCaption());

		// break_ot
		$this->break_ot->EditAttrs["class"] = "form-control";
		$this->break_ot->EditCustomAttributes = "";
		$this->break_ot->EditValue = $this->break_ot->CurrentValue;
		$this->break_ot->PlaceHolder = ew_RemoveHtml($this->break_ot->FldCaption());
		if (strval($this->break_ot->EditValue) <> "" && is_numeric($this->break_ot->EditValue)) $this->break_ot->EditValue = ew_FormatNumber($this->break_ot->EditValue, -2, -1, -2, 0);

		// early_permission
		$this->early_permission->EditAttrs["class"] = "form-control";
		$this->early_permission->EditCustomAttributes = "";
		$this->early_permission->EditValue = $this->early_permission->CurrentValue;
		$this->early_permission->PlaceHolder = ew_RemoveHtml($this->early_permission->FldCaption());

		// early_minute
		$this->early_minute->EditAttrs["class"] = "form-control";
		$this->early_minute->EditCustomAttributes = "";
		$this->early_minute->EditValue = $this->early_minute->CurrentValue;
		$this->early_minute->PlaceHolder = ew_RemoveHtml($this->early_minute->FldCaption());

		// early
		$this->early->EditAttrs["class"] = "form-control";
		$this->early->EditCustomAttributes = "";
		$this->early->EditValue = $this->early->CurrentValue;
		$this->early->PlaceHolder = ew_RemoveHtml($this->early->FldCaption());
		if (strval($this->early->EditValue) <> "" && is_numeric($this->early->EditValue)) $this->early->EditValue = ew_FormatNumber($this->early->EditValue, -2, -1, -2, 0);

		// scan_out
		$this->scan_out->EditAttrs["class"] = "form-control";
		$this->scan_out->EditCustomAttributes = "";
		$this->scan_out->EditValue = ew_FormatDateTime($this->scan_out->CurrentValue, 8);
		$this->scan_out->PlaceHolder = ew_RemoveHtml($this->scan_out->FldCaption());

		// att_id_out
		$this->att_id_out->EditAttrs["class"] = "form-control";
		$this->att_id_out->EditCustomAttributes = "";
		$this->att_id_out->EditValue = $this->att_id_out->CurrentValue;
		$this->att_id_out->PlaceHolder = ew_RemoveHtml($this->att_id_out->FldCaption());

		// durasi_minute
		$this->durasi_minute->EditAttrs["class"] = "form-control";
		$this->durasi_minute->EditCustomAttributes = "";
		$this->durasi_minute->EditValue = $this->durasi_minute->CurrentValue;
		$this->durasi_minute->PlaceHolder = ew_RemoveHtml($this->durasi_minute->FldCaption());

		// durasi
		$this->durasi->EditAttrs["class"] = "form-control";
		$this->durasi->EditCustomAttributes = "";
		$this->durasi->EditValue = $this->durasi->CurrentValue;
		$this->durasi->PlaceHolder = ew_RemoveHtml($this->durasi->FldCaption());
		if (strval($this->durasi->EditValue) <> "" && is_numeric($this->durasi->EditValue)) $this->durasi->EditValue = ew_FormatNumber($this->durasi->EditValue, -2, -1, -2, 0);

		// durasi_eot_minute
		$this->durasi_eot_minute->EditAttrs["class"] = "form-control";
		$this->durasi_eot_minute->EditCustomAttributes = "";
		$this->durasi_eot_minute->EditValue = $this->durasi_eot_minute->CurrentValue;
		$this->durasi_eot_minute->PlaceHolder = ew_RemoveHtml($this->durasi_eot_minute->FldCaption());

		// jk_count_as
		$this->jk_count_as->EditAttrs["class"] = "form-control";
		$this->jk_count_as->EditCustomAttributes = "";
		$this->jk_count_as->EditValue = $this->jk_count_as->CurrentValue;
		$this->jk_count_as->PlaceHolder = ew_RemoveHtml($this->jk_count_as->FldCaption());
		if (strval($this->jk_count_as->EditValue) <> "" && is_numeric($this->jk_count_as->EditValue)) $this->jk_count_as->EditValue = ew_FormatNumber($this->jk_count_as->EditValue, -2, -1, -2, 0);

		// status_jk
		$this->status_jk->EditAttrs["class"] = "form-control";
		$this->status_jk->EditCustomAttributes = "";
		$this->status_jk->EditValue = $this->status_jk->CurrentValue;
		$this->status_jk->PlaceHolder = ew_RemoveHtml($this->status_jk->FldCaption());

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
					if ($this->pegawai_id->Exportable) $Doc->ExportCaption($this->pegawai_id);
					if ($this->tgl_shift->Exportable) $Doc->ExportCaption($this->tgl_shift);
					if ($this->khusus_lembur->Exportable) $Doc->ExportCaption($this->khusus_lembur);
					if ($this->khusus_extra->Exportable) $Doc->ExportCaption($this->khusus_extra);
					if ($this->temp_id_auto->Exportable) $Doc->ExportCaption($this->temp_id_auto);
					if ($this->jdw_kerja_m_id->Exportable) $Doc->ExportCaption($this->jdw_kerja_m_id);
					if ($this->jk_id->Exportable) $Doc->ExportCaption($this->jk_id);
					if ($this->jns_dok->Exportable) $Doc->ExportCaption($this->jns_dok);
					if ($this->izin_jenis_id->Exportable) $Doc->ExportCaption($this->izin_jenis_id);
					if ($this->cuti_n_id->Exportable) $Doc->ExportCaption($this->cuti_n_id);
					if ($this->libur_umum->Exportable) $Doc->ExportCaption($this->libur_umum);
					if ($this->libur_rutin->Exportable) $Doc->ExportCaption($this->libur_rutin);
					if ($this->jk_ot->Exportable) $Doc->ExportCaption($this->jk_ot);
					if ($this->scan_in->Exportable) $Doc->ExportCaption($this->scan_in);
					if ($this->att_id_in->Exportable) $Doc->ExportCaption($this->att_id_in);
					if ($this->late_permission->Exportable) $Doc->ExportCaption($this->late_permission);
					if ($this->late_minute->Exportable) $Doc->ExportCaption($this->late_minute);
					if ($this->late->Exportable) $Doc->ExportCaption($this->late);
					if ($this->break_out->Exportable) $Doc->ExportCaption($this->break_out);
					if ($this->att_id_break1->Exportable) $Doc->ExportCaption($this->att_id_break1);
					if ($this->break_in->Exportable) $Doc->ExportCaption($this->break_in);
					if ($this->att_id_break2->Exportable) $Doc->ExportCaption($this->att_id_break2);
					if ($this->break_minute->Exportable) $Doc->ExportCaption($this->break_minute);
					if ($this->break->Exportable) $Doc->ExportCaption($this->break);
					if ($this->break_ot_minute->Exportable) $Doc->ExportCaption($this->break_ot_minute);
					if ($this->break_ot->Exportable) $Doc->ExportCaption($this->break_ot);
					if ($this->early_permission->Exportable) $Doc->ExportCaption($this->early_permission);
					if ($this->early_minute->Exportable) $Doc->ExportCaption($this->early_minute);
					if ($this->early->Exportable) $Doc->ExportCaption($this->early);
					if ($this->scan_out->Exportable) $Doc->ExportCaption($this->scan_out);
					if ($this->att_id_out->Exportable) $Doc->ExportCaption($this->att_id_out);
					if ($this->durasi_minute->Exportable) $Doc->ExportCaption($this->durasi_minute);
					if ($this->durasi->Exportable) $Doc->ExportCaption($this->durasi);
					if ($this->durasi_eot_minute->Exportable) $Doc->ExportCaption($this->durasi_eot_minute);
					if ($this->jk_count_as->Exportable) $Doc->ExportCaption($this->jk_count_as);
					if ($this->status_jk->Exportable) $Doc->ExportCaption($this->status_jk);
					if ($this->keterangan->Exportable) $Doc->ExportCaption($this->keterangan);
				} else {
					if ($this->pegawai_id->Exportable) $Doc->ExportCaption($this->pegawai_id);
					if ($this->tgl_shift->Exportable) $Doc->ExportCaption($this->tgl_shift);
					if ($this->khusus_lembur->Exportable) $Doc->ExportCaption($this->khusus_lembur);
					if ($this->khusus_extra->Exportable) $Doc->ExportCaption($this->khusus_extra);
					if ($this->temp_id_auto->Exportable) $Doc->ExportCaption($this->temp_id_auto);
					if ($this->jdw_kerja_m_id->Exportable) $Doc->ExportCaption($this->jdw_kerja_m_id);
					if ($this->jk_id->Exportable) $Doc->ExportCaption($this->jk_id);
					if ($this->jns_dok->Exportable) $Doc->ExportCaption($this->jns_dok);
					if ($this->izin_jenis_id->Exportable) $Doc->ExportCaption($this->izin_jenis_id);
					if ($this->cuti_n_id->Exportable) $Doc->ExportCaption($this->cuti_n_id);
					if ($this->libur_umum->Exportable) $Doc->ExportCaption($this->libur_umum);
					if ($this->libur_rutin->Exportable) $Doc->ExportCaption($this->libur_rutin);
					if ($this->jk_ot->Exportable) $Doc->ExportCaption($this->jk_ot);
					if ($this->scan_in->Exportable) $Doc->ExportCaption($this->scan_in);
					if ($this->att_id_in->Exportable) $Doc->ExportCaption($this->att_id_in);
					if ($this->late_permission->Exportable) $Doc->ExportCaption($this->late_permission);
					if ($this->late_minute->Exportable) $Doc->ExportCaption($this->late_minute);
					if ($this->late->Exportable) $Doc->ExportCaption($this->late);
					if ($this->break_out->Exportable) $Doc->ExportCaption($this->break_out);
					if ($this->att_id_break1->Exportable) $Doc->ExportCaption($this->att_id_break1);
					if ($this->break_in->Exportable) $Doc->ExportCaption($this->break_in);
					if ($this->att_id_break2->Exportable) $Doc->ExportCaption($this->att_id_break2);
					if ($this->break_minute->Exportable) $Doc->ExportCaption($this->break_minute);
					if ($this->break->Exportable) $Doc->ExportCaption($this->break);
					if ($this->break_ot_minute->Exportable) $Doc->ExportCaption($this->break_ot_minute);
					if ($this->break_ot->Exportable) $Doc->ExportCaption($this->break_ot);
					if ($this->early_permission->Exportable) $Doc->ExportCaption($this->early_permission);
					if ($this->early_minute->Exportable) $Doc->ExportCaption($this->early_minute);
					if ($this->early->Exportable) $Doc->ExportCaption($this->early);
					if ($this->scan_out->Exportable) $Doc->ExportCaption($this->scan_out);
					if ($this->att_id_out->Exportable) $Doc->ExportCaption($this->att_id_out);
					if ($this->durasi_minute->Exportable) $Doc->ExportCaption($this->durasi_minute);
					if ($this->durasi->Exportable) $Doc->ExportCaption($this->durasi);
					if ($this->durasi_eot_minute->Exportable) $Doc->ExportCaption($this->durasi_eot_minute);
					if ($this->jk_count_as->Exportable) $Doc->ExportCaption($this->jk_count_as);
					if ($this->status_jk->Exportable) $Doc->ExportCaption($this->status_jk);
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
						if ($this->tgl_shift->Exportable) $Doc->ExportField($this->tgl_shift);
						if ($this->khusus_lembur->Exportable) $Doc->ExportField($this->khusus_lembur);
						if ($this->khusus_extra->Exportable) $Doc->ExportField($this->khusus_extra);
						if ($this->temp_id_auto->Exportable) $Doc->ExportField($this->temp_id_auto);
						if ($this->jdw_kerja_m_id->Exportable) $Doc->ExportField($this->jdw_kerja_m_id);
						if ($this->jk_id->Exportable) $Doc->ExportField($this->jk_id);
						if ($this->jns_dok->Exportable) $Doc->ExportField($this->jns_dok);
						if ($this->izin_jenis_id->Exportable) $Doc->ExportField($this->izin_jenis_id);
						if ($this->cuti_n_id->Exportable) $Doc->ExportField($this->cuti_n_id);
						if ($this->libur_umum->Exportable) $Doc->ExportField($this->libur_umum);
						if ($this->libur_rutin->Exportable) $Doc->ExportField($this->libur_rutin);
						if ($this->jk_ot->Exportable) $Doc->ExportField($this->jk_ot);
						if ($this->scan_in->Exportable) $Doc->ExportField($this->scan_in);
						if ($this->att_id_in->Exportable) $Doc->ExportField($this->att_id_in);
						if ($this->late_permission->Exportable) $Doc->ExportField($this->late_permission);
						if ($this->late_minute->Exportable) $Doc->ExportField($this->late_minute);
						if ($this->late->Exportable) $Doc->ExportField($this->late);
						if ($this->break_out->Exportable) $Doc->ExportField($this->break_out);
						if ($this->att_id_break1->Exportable) $Doc->ExportField($this->att_id_break1);
						if ($this->break_in->Exportable) $Doc->ExportField($this->break_in);
						if ($this->att_id_break2->Exportable) $Doc->ExportField($this->att_id_break2);
						if ($this->break_minute->Exportable) $Doc->ExportField($this->break_minute);
						if ($this->break->Exportable) $Doc->ExportField($this->break);
						if ($this->break_ot_minute->Exportable) $Doc->ExportField($this->break_ot_minute);
						if ($this->break_ot->Exportable) $Doc->ExportField($this->break_ot);
						if ($this->early_permission->Exportable) $Doc->ExportField($this->early_permission);
						if ($this->early_minute->Exportable) $Doc->ExportField($this->early_minute);
						if ($this->early->Exportable) $Doc->ExportField($this->early);
						if ($this->scan_out->Exportable) $Doc->ExportField($this->scan_out);
						if ($this->att_id_out->Exportable) $Doc->ExportField($this->att_id_out);
						if ($this->durasi_minute->Exportable) $Doc->ExportField($this->durasi_minute);
						if ($this->durasi->Exportable) $Doc->ExportField($this->durasi);
						if ($this->durasi_eot_minute->Exportable) $Doc->ExportField($this->durasi_eot_minute);
						if ($this->jk_count_as->Exportable) $Doc->ExportField($this->jk_count_as);
						if ($this->status_jk->Exportable) $Doc->ExportField($this->status_jk);
						if ($this->keterangan->Exportable) $Doc->ExportField($this->keterangan);
					} else {
						if ($this->pegawai_id->Exportable) $Doc->ExportField($this->pegawai_id);
						if ($this->tgl_shift->Exportable) $Doc->ExportField($this->tgl_shift);
						if ($this->khusus_lembur->Exportable) $Doc->ExportField($this->khusus_lembur);
						if ($this->khusus_extra->Exportable) $Doc->ExportField($this->khusus_extra);
						if ($this->temp_id_auto->Exportable) $Doc->ExportField($this->temp_id_auto);
						if ($this->jdw_kerja_m_id->Exportable) $Doc->ExportField($this->jdw_kerja_m_id);
						if ($this->jk_id->Exportable) $Doc->ExportField($this->jk_id);
						if ($this->jns_dok->Exportable) $Doc->ExportField($this->jns_dok);
						if ($this->izin_jenis_id->Exportable) $Doc->ExportField($this->izin_jenis_id);
						if ($this->cuti_n_id->Exportable) $Doc->ExportField($this->cuti_n_id);
						if ($this->libur_umum->Exportable) $Doc->ExportField($this->libur_umum);
						if ($this->libur_rutin->Exportable) $Doc->ExportField($this->libur_rutin);
						if ($this->jk_ot->Exportable) $Doc->ExportField($this->jk_ot);
						if ($this->scan_in->Exportable) $Doc->ExportField($this->scan_in);
						if ($this->att_id_in->Exportable) $Doc->ExportField($this->att_id_in);
						if ($this->late_permission->Exportable) $Doc->ExportField($this->late_permission);
						if ($this->late_minute->Exportable) $Doc->ExportField($this->late_minute);
						if ($this->late->Exportable) $Doc->ExportField($this->late);
						if ($this->break_out->Exportable) $Doc->ExportField($this->break_out);
						if ($this->att_id_break1->Exportable) $Doc->ExportField($this->att_id_break1);
						if ($this->break_in->Exportable) $Doc->ExportField($this->break_in);
						if ($this->att_id_break2->Exportable) $Doc->ExportField($this->att_id_break2);
						if ($this->break_minute->Exportable) $Doc->ExportField($this->break_minute);
						if ($this->break->Exportable) $Doc->ExportField($this->break);
						if ($this->break_ot_minute->Exportable) $Doc->ExportField($this->break_ot_minute);
						if ($this->break_ot->Exportable) $Doc->ExportField($this->break_ot);
						if ($this->early_permission->Exportable) $Doc->ExportField($this->early_permission);
						if ($this->early_minute->Exportable) $Doc->ExportField($this->early_minute);
						if ($this->early->Exportable) $Doc->ExportField($this->early);
						if ($this->scan_out->Exportable) $Doc->ExportField($this->scan_out);
						if ($this->att_id_out->Exportable) $Doc->ExportField($this->att_id_out);
						if ($this->durasi_minute->Exportable) $Doc->ExportField($this->durasi_minute);
						if ($this->durasi->Exportable) $Doc->ExportField($this->durasi);
						if ($this->durasi_eot_minute->Exportable) $Doc->ExportField($this->durasi_eot_minute);
						if ($this->jk_count_as->Exportable) $Doc->ExportField($this->jk_count_as);
						if ($this->status_jk->Exportable) $Doc->ExportField($this->status_jk);
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
