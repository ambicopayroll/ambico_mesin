<?php

// Global variable for table object
$device = NULL;

//
// Table class for device
//
class cdevice extends cTable {
	var $sn;
	var $activation_code;
	var $act_code_realtime;
	var $device_name;
	var $comm_key;
	var $dev_id;
	var $comm_type;
	var $ip_address;
	var $id_type;
	var $dev_type;
	var $serial_port;
	var $baud_rate;
	var $ethernet_port;
	var $layar;
	var $alg_ver;
	var $use_realtime;
	var $group_realtime;
	var $last_download;
	var $ATTLOGStamp;
	var $OPERLOGStamp;
	var $ATTPHOTOStamp;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'device';
		$this->TableName = 'device';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`device`";
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

		// sn
		$this->sn = new cField('device', 'device', 'x_sn', 'sn', '`sn`', '`sn`', 200, -1, FALSE, '`sn`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->sn->Sortable = TRUE; // Allow sort
		$this->fields['sn'] = &$this->sn;

		// activation_code
		$this->activation_code = new cField('device', 'device', 'x_activation_code', 'activation_code', '`activation_code`', '`activation_code`', 200, -1, FALSE, '`activation_code`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->activation_code->Sortable = TRUE; // Allow sort
		$this->fields['activation_code'] = &$this->activation_code;

		// act_code_realtime
		$this->act_code_realtime = new cField('device', 'device', 'x_act_code_realtime', 'act_code_realtime', '`act_code_realtime`', '`act_code_realtime`', 200, -1, FALSE, '`act_code_realtime`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->act_code_realtime->Sortable = TRUE; // Allow sort
		$this->fields['act_code_realtime'] = &$this->act_code_realtime;

		// device_name
		$this->device_name = new cField('device', 'device', 'x_device_name', 'device_name', '`device_name`', '`device_name`', 200, -1, FALSE, '`device_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->device_name->Sortable = TRUE; // Allow sort
		$this->fields['device_name'] = &$this->device_name;

		// comm_key
		$this->comm_key = new cField('device', 'device', 'x_comm_key', 'comm_key', '`comm_key`', '`comm_key`', 3, -1, FALSE, '`comm_key`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->comm_key->Sortable = TRUE; // Allow sort
		$this->comm_key->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['comm_key'] = &$this->comm_key;

		// dev_id
		$this->dev_id = new cField('device', 'device', 'x_dev_id', 'dev_id', '`dev_id`', '`dev_id`', 16, -1, FALSE, '`dev_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->dev_id->Sortable = TRUE; // Allow sort
		$this->dev_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['dev_id'] = &$this->dev_id;

		// comm_type
		$this->comm_type = new cField('device', 'device', 'x_comm_type', 'comm_type', '`comm_type`', '`comm_type`', 16, -1, FALSE, '`comm_type`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->comm_type->Sortable = TRUE; // Allow sort
		$this->comm_type->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['comm_type'] = &$this->comm_type;

		// ip_address
		$this->ip_address = new cField('device', 'device', 'x_ip_address', 'ip_address', '`ip_address`', '`ip_address`', 200, -1, FALSE, '`ip_address`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->ip_address->Sortable = TRUE; // Allow sort
		$this->fields['ip_address'] = &$this->ip_address;

		// id_type
		$this->id_type = new cField('device', 'device', 'x_id_type', 'id_type', '`id_type`', '`id_type`', 3, -1, FALSE, '`id_type`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->id_type->Sortable = TRUE; // Allow sort
		$this->id_type->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_type'] = &$this->id_type;

		// dev_type
		$this->dev_type = new cField('device', 'device', 'x_dev_type', 'dev_type', '`dev_type`', '`dev_type`', 16, -1, FALSE, '`dev_type`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->dev_type->Sortable = TRUE; // Allow sort
		$this->dev_type->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['dev_type'] = &$this->dev_type;

		// serial_port
		$this->serial_port = new cField('device', 'device', 'x_serial_port', 'serial_port', '`serial_port`', '`serial_port`', 200, -1, FALSE, '`serial_port`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->serial_port->Sortable = TRUE; // Allow sort
		$this->fields['serial_port'] = &$this->serial_port;

		// baud_rate
		$this->baud_rate = new cField('device', 'device', 'x_baud_rate', 'baud_rate', '`baud_rate`', '`baud_rate`', 200, -1, FALSE, '`baud_rate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->baud_rate->Sortable = TRUE; // Allow sort
		$this->fields['baud_rate'] = &$this->baud_rate;

		// ethernet_port
		$this->ethernet_port = new cField('device', 'device', 'x_ethernet_port', 'ethernet_port', '`ethernet_port`', '`ethernet_port`', 200, -1, FALSE, '`ethernet_port`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->ethernet_port->Sortable = TRUE; // Allow sort
		$this->fields['ethernet_port'] = &$this->ethernet_port;

		// layar
		$this->layar = new cField('device', 'device', 'x_layar', 'layar', '`layar`', '`layar`', 16, -1, FALSE, '`layar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->layar->Sortable = TRUE; // Allow sort
		$this->layar->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['layar'] = &$this->layar;

		// alg_ver
		$this->alg_ver = new cField('device', 'device', 'x_alg_ver', 'alg_ver', '`alg_ver`', '`alg_ver`', 16, -1, FALSE, '`alg_ver`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->alg_ver->Sortable = TRUE; // Allow sort
		$this->alg_ver->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['alg_ver'] = &$this->alg_ver;

		// use_realtime
		$this->use_realtime = new cField('device', 'device', 'x_use_realtime', 'use_realtime', '`use_realtime`', '`use_realtime`', 16, -1, FALSE, '`use_realtime`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->use_realtime->Sortable = TRUE; // Allow sort
		$this->use_realtime->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['use_realtime'] = &$this->use_realtime;

		// group_realtime
		$this->group_realtime = new cField('device', 'device', 'x_group_realtime', 'group_realtime', '`group_realtime`', '`group_realtime`', 16, -1, FALSE, '`group_realtime`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->group_realtime->Sortable = TRUE; // Allow sort
		$this->group_realtime->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['group_realtime'] = &$this->group_realtime;

		// last_download
		$this->last_download = new cField('device', 'device', 'x_last_download', 'last_download', '`last_download`', ew_CastDateFieldForLike('`last_download`', 0, "DB"), 133, 0, FALSE, '`last_download`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->last_download->Sortable = TRUE; // Allow sort
		$this->last_download->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['last_download'] = &$this->last_download;

		// ATTLOGStamp
		$this->ATTLOGStamp = new cField('device', 'device', 'x_ATTLOGStamp', 'ATTLOGStamp', '`ATTLOGStamp`', '`ATTLOGStamp`', 200, -1, FALSE, '`ATTLOGStamp`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->ATTLOGStamp->Sortable = TRUE; // Allow sort
		$this->fields['ATTLOGStamp'] = &$this->ATTLOGStamp;

		// OPERLOGStamp
		$this->OPERLOGStamp = new cField('device', 'device', 'x_OPERLOGStamp', 'OPERLOGStamp', '`OPERLOGStamp`', '`OPERLOGStamp`', 200, -1, FALSE, '`OPERLOGStamp`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->OPERLOGStamp->Sortable = TRUE; // Allow sort
		$this->fields['OPERLOGStamp'] = &$this->OPERLOGStamp;

		// ATTPHOTOStamp
		$this->ATTPHOTOStamp = new cField('device', 'device', 'x_ATTPHOTOStamp', 'ATTPHOTOStamp', '`ATTPHOTOStamp`', '`ATTPHOTOStamp`', 200, -1, FALSE, '`ATTPHOTOStamp`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->ATTPHOTOStamp->Sortable = TRUE; // Allow sort
		$this->fields['ATTPHOTOStamp'] = &$this->ATTPHOTOStamp;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`device`";
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
			if (array_key_exists('sn', $rs))
				ew_AddFilter($where, ew_QuotedName('sn', $this->DBID) . '=' . ew_QuotedValue($rs['sn'], $this->sn->FldDataType, $this->DBID));
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
		return "`sn` = '@sn@'";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		$sKeyFilter = str_replace("@sn@", ew_AdjustSql($this->sn->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "devicelist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "devicelist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("deviceview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("deviceview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "deviceadd.php?" . $this->UrlParm($parm);
		else
			$url = "deviceadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("deviceedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("deviceadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("devicedelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "sn:" . ew_VarToJson($this->sn->CurrentValue, "string", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->sn->CurrentValue)) {
			$sUrl .= "sn=" . urlencode($this->sn->CurrentValue);
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
			if ($isPost && isset($_POST["sn"]))
				$arKeys[] = ew_StripSlashes($_POST["sn"]);
			elseif (isset($_GET["sn"]))
				$arKeys[] = ew_StripSlashes($_GET["sn"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
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
			$this->sn->CurrentValue = $key;
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
		$this->sn->setDbValue($rs->fields('sn'));
		$this->activation_code->setDbValue($rs->fields('activation_code'));
		$this->act_code_realtime->setDbValue($rs->fields('act_code_realtime'));
		$this->device_name->setDbValue($rs->fields('device_name'));
		$this->comm_key->setDbValue($rs->fields('comm_key'));
		$this->dev_id->setDbValue($rs->fields('dev_id'));
		$this->comm_type->setDbValue($rs->fields('comm_type'));
		$this->ip_address->setDbValue($rs->fields('ip_address'));
		$this->id_type->setDbValue($rs->fields('id_type'));
		$this->dev_type->setDbValue($rs->fields('dev_type'));
		$this->serial_port->setDbValue($rs->fields('serial_port'));
		$this->baud_rate->setDbValue($rs->fields('baud_rate'));
		$this->ethernet_port->setDbValue($rs->fields('ethernet_port'));
		$this->layar->setDbValue($rs->fields('layar'));
		$this->alg_ver->setDbValue($rs->fields('alg_ver'));
		$this->use_realtime->setDbValue($rs->fields('use_realtime'));
		$this->group_realtime->setDbValue($rs->fields('group_realtime'));
		$this->last_download->setDbValue($rs->fields('last_download'));
		$this->ATTLOGStamp->setDbValue($rs->fields('ATTLOGStamp'));
		$this->OPERLOGStamp->setDbValue($rs->fields('OPERLOGStamp'));
		$this->ATTPHOTOStamp->setDbValue($rs->fields('ATTPHOTOStamp'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// sn
		// activation_code
		// act_code_realtime
		// device_name
		// comm_key
		// dev_id
		// comm_type
		// ip_address
		// id_type
		// dev_type
		// serial_port
		// baud_rate
		// ethernet_port
		// layar
		// alg_ver
		// use_realtime
		// group_realtime
		// last_download
		// ATTLOGStamp
		// OPERLOGStamp
		// ATTPHOTOStamp
		// sn

		$this->sn->ViewValue = $this->sn->CurrentValue;
		$this->sn->ViewCustomAttributes = "";

		// activation_code
		$this->activation_code->ViewValue = $this->activation_code->CurrentValue;
		$this->activation_code->ViewCustomAttributes = "";

		// act_code_realtime
		$this->act_code_realtime->ViewValue = $this->act_code_realtime->CurrentValue;
		$this->act_code_realtime->ViewCustomAttributes = "";

		// device_name
		$this->device_name->ViewValue = $this->device_name->CurrentValue;
		$this->device_name->ViewCustomAttributes = "";

		// comm_key
		$this->comm_key->ViewValue = $this->comm_key->CurrentValue;
		$this->comm_key->ViewCustomAttributes = "";

		// dev_id
		$this->dev_id->ViewValue = $this->dev_id->CurrentValue;
		$this->dev_id->ViewCustomAttributes = "";

		// comm_type
		$this->comm_type->ViewValue = $this->comm_type->CurrentValue;
		$this->comm_type->ViewCustomAttributes = "";

		// ip_address
		$this->ip_address->ViewValue = $this->ip_address->CurrentValue;
		$this->ip_address->ViewCustomAttributes = "";

		// id_type
		$this->id_type->ViewValue = $this->id_type->CurrentValue;
		$this->id_type->ViewCustomAttributes = "";

		// dev_type
		$this->dev_type->ViewValue = $this->dev_type->CurrentValue;
		$this->dev_type->ViewCustomAttributes = "";

		// serial_port
		$this->serial_port->ViewValue = $this->serial_port->CurrentValue;
		$this->serial_port->ViewCustomAttributes = "";

		// baud_rate
		$this->baud_rate->ViewValue = $this->baud_rate->CurrentValue;
		$this->baud_rate->ViewCustomAttributes = "";

		// ethernet_port
		$this->ethernet_port->ViewValue = $this->ethernet_port->CurrentValue;
		$this->ethernet_port->ViewCustomAttributes = "";

		// layar
		$this->layar->ViewValue = $this->layar->CurrentValue;
		$this->layar->ViewCustomAttributes = "";

		// alg_ver
		$this->alg_ver->ViewValue = $this->alg_ver->CurrentValue;
		$this->alg_ver->ViewCustomAttributes = "";

		// use_realtime
		$this->use_realtime->ViewValue = $this->use_realtime->CurrentValue;
		$this->use_realtime->ViewCustomAttributes = "";

		// group_realtime
		$this->group_realtime->ViewValue = $this->group_realtime->CurrentValue;
		$this->group_realtime->ViewCustomAttributes = "";

		// last_download
		$this->last_download->ViewValue = $this->last_download->CurrentValue;
		$this->last_download->ViewValue = ew_FormatDateTime($this->last_download->ViewValue, 0);
		$this->last_download->ViewCustomAttributes = "";

		// ATTLOGStamp
		$this->ATTLOGStamp->ViewValue = $this->ATTLOGStamp->CurrentValue;
		$this->ATTLOGStamp->ViewCustomAttributes = "";

		// OPERLOGStamp
		$this->OPERLOGStamp->ViewValue = $this->OPERLOGStamp->CurrentValue;
		$this->OPERLOGStamp->ViewCustomAttributes = "";

		// ATTPHOTOStamp
		$this->ATTPHOTOStamp->ViewValue = $this->ATTPHOTOStamp->CurrentValue;
		$this->ATTPHOTOStamp->ViewCustomAttributes = "";

		// sn
		$this->sn->LinkCustomAttributes = "";
		$this->sn->HrefValue = "";
		$this->sn->TooltipValue = "";

		// activation_code
		$this->activation_code->LinkCustomAttributes = "";
		$this->activation_code->HrefValue = "";
		$this->activation_code->TooltipValue = "";

		// act_code_realtime
		$this->act_code_realtime->LinkCustomAttributes = "";
		$this->act_code_realtime->HrefValue = "";
		$this->act_code_realtime->TooltipValue = "";

		// device_name
		$this->device_name->LinkCustomAttributes = "";
		$this->device_name->HrefValue = "";
		$this->device_name->TooltipValue = "";

		// comm_key
		$this->comm_key->LinkCustomAttributes = "";
		$this->comm_key->HrefValue = "";
		$this->comm_key->TooltipValue = "";

		// dev_id
		$this->dev_id->LinkCustomAttributes = "";
		$this->dev_id->HrefValue = "";
		$this->dev_id->TooltipValue = "";

		// comm_type
		$this->comm_type->LinkCustomAttributes = "";
		$this->comm_type->HrefValue = "";
		$this->comm_type->TooltipValue = "";

		// ip_address
		$this->ip_address->LinkCustomAttributes = "";
		$this->ip_address->HrefValue = "";
		$this->ip_address->TooltipValue = "";

		// id_type
		$this->id_type->LinkCustomAttributes = "";
		$this->id_type->HrefValue = "";
		$this->id_type->TooltipValue = "";

		// dev_type
		$this->dev_type->LinkCustomAttributes = "";
		$this->dev_type->HrefValue = "";
		$this->dev_type->TooltipValue = "";

		// serial_port
		$this->serial_port->LinkCustomAttributes = "";
		$this->serial_port->HrefValue = "";
		$this->serial_port->TooltipValue = "";

		// baud_rate
		$this->baud_rate->LinkCustomAttributes = "";
		$this->baud_rate->HrefValue = "";
		$this->baud_rate->TooltipValue = "";

		// ethernet_port
		$this->ethernet_port->LinkCustomAttributes = "";
		$this->ethernet_port->HrefValue = "";
		$this->ethernet_port->TooltipValue = "";

		// layar
		$this->layar->LinkCustomAttributes = "";
		$this->layar->HrefValue = "";
		$this->layar->TooltipValue = "";

		// alg_ver
		$this->alg_ver->LinkCustomAttributes = "";
		$this->alg_ver->HrefValue = "";
		$this->alg_ver->TooltipValue = "";

		// use_realtime
		$this->use_realtime->LinkCustomAttributes = "";
		$this->use_realtime->HrefValue = "";
		$this->use_realtime->TooltipValue = "";

		// group_realtime
		$this->group_realtime->LinkCustomAttributes = "";
		$this->group_realtime->HrefValue = "";
		$this->group_realtime->TooltipValue = "";

		// last_download
		$this->last_download->LinkCustomAttributes = "";
		$this->last_download->HrefValue = "";
		$this->last_download->TooltipValue = "";

		// ATTLOGStamp
		$this->ATTLOGStamp->LinkCustomAttributes = "";
		$this->ATTLOGStamp->HrefValue = "";
		$this->ATTLOGStamp->TooltipValue = "";

		// OPERLOGStamp
		$this->OPERLOGStamp->LinkCustomAttributes = "";
		$this->OPERLOGStamp->HrefValue = "";
		$this->OPERLOGStamp->TooltipValue = "";

		// ATTPHOTOStamp
		$this->ATTPHOTOStamp->LinkCustomAttributes = "";
		$this->ATTPHOTOStamp->HrefValue = "";
		$this->ATTPHOTOStamp->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// sn
		$this->sn->EditAttrs["class"] = "form-control";
		$this->sn->EditCustomAttributes = "";
		$this->sn->EditValue = $this->sn->CurrentValue;
		$this->sn->ViewCustomAttributes = "";

		// activation_code
		$this->activation_code->EditAttrs["class"] = "form-control";
		$this->activation_code->EditCustomAttributes = "";
		$this->activation_code->EditValue = $this->activation_code->CurrentValue;
		$this->activation_code->PlaceHolder = ew_RemoveHtml($this->activation_code->FldCaption());

		// act_code_realtime
		$this->act_code_realtime->EditAttrs["class"] = "form-control";
		$this->act_code_realtime->EditCustomAttributes = "";
		$this->act_code_realtime->EditValue = $this->act_code_realtime->CurrentValue;
		$this->act_code_realtime->PlaceHolder = ew_RemoveHtml($this->act_code_realtime->FldCaption());

		// device_name
		$this->device_name->EditAttrs["class"] = "form-control";
		$this->device_name->EditCustomAttributes = "";
		$this->device_name->EditValue = $this->device_name->CurrentValue;
		$this->device_name->PlaceHolder = ew_RemoveHtml($this->device_name->FldCaption());

		// comm_key
		$this->comm_key->EditAttrs["class"] = "form-control";
		$this->comm_key->EditCustomAttributes = "";
		$this->comm_key->EditValue = $this->comm_key->CurrentValue;
		$this->comm_key->PlaceHolder = ew_RemoveHtml($this->comm_key->FldCaption());

		// dev_id
		$this->dev_id->EditAttrs["class"] = "form-control";
		$this->dev_id->EditCustomAttributes = "";
		$this->dev_id->EditValue = $this->dev_id->CurrentValue;
		$this->dev_id->PlaceHolder = ew_RemoveHtml($this->dev_id->FldCaption());

		// comm_type
		$this->comm_type->EditAttrs["class"] = "form-control";
		$this->comm_type->EditCustomAttributes = "";
		$this->comm_type->EditValue = $this->comm_type->CurrentValue;
		$this->comm_type->PlaceHolder = ew_RemoveHtml($this->comm_type->FldCaption());

		// ip_address
		$this->ip_address->EditAttrs["class"] = "form-control";
		$this->ip_address->EditCustomAttributes = "";
		$this->ip_address->EditValue = $this->ip_address->CurrentValue;
		$this->ip_address->PlaceHolder = ew_RemoveHtml($this->ip_address->FldCaption());

		// id_type
		$this->id_type->EditAttrs["class"] = "form-control";
		$this->id_type->EditCustomAttributes = "";
		$this->id_type->EditValue = $this->id_type->CurrentValue;
		$this->id_type->PlaceHolder = ew_RemoveHtml($this->id_type->FldCaption());

		// dev_type
		$this->dev_type->EditAttrs["class"] = "form-control";
		$this->dev_type->EditCustomAttributes = "";
		$this->dev_type->EditValue = $this->dev_type->CurrentValue;
		$this->dev_type->PlaceHolder = ew_RemoveHtml($this->dev_type->FldCaption());

		// serial_port
		$this->serial_port->EditAttrs["class"] = "form-control";
		$this->serial_port->EditCustomAttributes = "";
		$this->serial_port->EditValue = $this->serial_port->CurrentValue;
		$this->serial_port->PlaceHolder = ew_RemoveHtml($this->serial_port->FldCaption());

		// baud_rate
		$this->baud_rate->EditAttrs["class"] = "form-control";
		$this->baud_rate->EditCustomAttributes = "";
		$this->baud_rate->EditValue = $this->baud_rate->CurrentValue;
		$this->baud_rate->PlaceHolder = ew_RemoveHtml($this->baud_rate->FldCaption());

		// ethernet_port
		$this->ethernet_port->EditAttrs["class"] = "form-control";
		$this->ethernet_port->EditCustomAttributes = "";
		$this->ethernet_port->EditValue = $this->ethernet_port->CurrentValue;
		$this->ethernet_port->PlaceHolder = ew_RemoveHtml($this->ethernet_port->FldCaption());

		// layar
		$this->layar->EditAttrs["class"] = "form-control";
		$this->layar->EditCustomAttributes = "";
		$this->layar->EditValue = $this->layar->CurrentValue;
		$this->layar->PlaceHolder = ew_RemoveHtml($this->layar->FldCaption());

		// alg_ver
		$this->alg_ver->EditAttrs["class"] = "form-control";
		$this->alg_ver->EditCustomAttributes = "";
		$this->alg_ver->EditValue = $this->alg_ver->CurrentValue;
		$this->alg_ver->PlaceHolder = ew_RemoveHtml($this->alg_ver->FldCaption());

		// use_realtime
		$this->use_realtime->EditAttrs["class"] = "form-control";
		$this->use_realtime->EditCustomAttributes = "";
		$this->use_realtime->EditValue = $this->use_realtime->CurrentValue;
		$this->use_realtime->PlaceHolder = ew_RemoveHtml($this->use_realtime->FldCaption());

		// group_realtime
		$this->group_realtime->EditAttrs["class"] = "form-control";
		$this->group_realtime->EditCustomAttributes = "";
		$this->group_realtime->EditValue = $this->group_realtime->CurrentValue;
		$this->group_realtime->PlaceHolder = ew_RemoveHtml($this->group_realtime->FldCaption());

		// last_download
		$this->last_download->EditAttrs["class"] = "form-control";
		$this->last_download->EditCustomAttributes = "";
		$this->last_download->EditValue = ew_FormatDateTime($this->last_download->CurrentValue, 8);
		$this->last_download->PlaceHolder = ew_RemoveHtml($this->last_download->FldCaption());

		// ATTLOGStamp
		$this->ATTLOGStamp->EditAttrs["class"] = "form-control";
		$this->ATTLOGStamp->EditCustomAttributes = "";
		$this->ATTLOGStamp->EditValue = $this->ATTLOGStamp->CurrentValue;
		$this->ATTLOGStamp->PlaceHolder = ew_RemoveHtml($this->ATTLOGStamp->FldCaption());

		// OPERLOGStamp
		$this->OPERLOGStamp->EditAttrs["class"] = "form-control";
		$this->OPERLOGStamp->EditCustomAttributes = "";
		$this->OPERLOGStamp->EditValue = $this->OPERLOGStamp->CurrentValue;
		$this->OPERLOGStamp->PlaceHolder = ew_RemoveHtml($this->OPERLOGStamp->FldCaption());

		// ATTPHOTOStamp
		$this->ATTPHOTOStamp->EditAttrs["class"] = "form-control";
		$this->ATTPHOTOStamp->EditCustomAttributes = "";
		$this->ATTPHOTOStamp->EditValue = $this->ATTPHOTOStamp->CurrentValue;
		$this->ATTPHOTOStamp->PlaceHolder = ew_RemoveHtml($this->ATTPHOTOStamp->FldCaption());

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
					if ($this->sn->Exportable) $Doc->ExportCaption($this->sn);
					if ($this->activation_code->Exportable) $Doc->ExportCaption($this->activation_code);
					if ($this->act_code_realtime->Exportable) $Doc->ExportCaption($this->act_code_realtime);
					if ($this->device_name->Exportable) $Doc->ExportCaption($this->device_name);
					if ($this->comm_key->Exportable) $Doc->ExportCaption($this->comm_key);
					if ($this->dev_id->Exportable) $Doc->ExportCaption($this->dev_id);
					if ($this->comm_type->Exportable) $Doc->ExportCaption($this->comm_type);
					if ($this->ip_address->Exportable) $Doc->ExportCaption($this->ip_address);
					if ($this->id_type->Exportable) $Doc->ExportCaption($this->id_type);
					if ($this->dev_type->Exportable) $Doc->ExportCaption($this->dev_type);
					if ($this->serial_port->Exportable) $Doc->ExportCaption($this->serial_port);
					if ($this->baud_rate->Exportable) $Doc->ExportCaption($this->baud_rate);
					if ($this->ethernet_port->Exportable) $Doc->ExportCaption($this->ethernet_port);
					if ($this->layar->Exportable) $Doc->ExportCaption($this->layar);
					if ($this->alg_ver->Exportable) $Doc->ExportCaption($this->alg_ver);
					if ($this->use_realtime->Exportable) $Doc->ExportCaption($this->use_realtime);
					if ($this->group_realtime->Exportable) $Doc->ExportCaption($this->group_realtime);
					if ($this->last_download->Exportable) $Doc->ExportCaption($this->last_download);
					if ($this->ATTLOGStamp->Exportable) $Doc->ExportCaption($this->ATTLOGStamp);
					if ($this->OPERLOGStamp->Exportable) $Doc->ExportCaption($this->OPERLOGStamp);
					if ($this->ATTPHOTOStamp->Exportable) $Doc->ExportCaption($this->ATTPHOTOStamp);
				} else {
					if ($this->sn->Exportable) $Doc->ExportCaption($this->sn);
					if ($this->activation_code->Exportable) $Doc->ExportCaption($this->activation_code);
					if ($this->act_code_realtime->Exportable) $Doc->ExportCaption($this->act_code_realtime);
					if ($this->device_name->Exportable) $Doc->ExportCaption($this->device_name);
					if ($this->comm_key->Exportable) $Doc->ExportCaption($this->comm_key);
					if ($this->dev_id->Exportable) $Doc->ExportCaption($this->dev_id);
					if ($this->comm_type->Exportable) $Doc->ExportCaption($this->comm_type);
					if ($this->ip_address->Exportable) $Doc->ExportCaption($this->ip_address);
					if ($this->id_type->Exportable) $Doc->ExportCaption($this->id_type);
					if ($this->dev_type->Exportable) $Doc->ExportCaption($this->dev_type);
					if ($this->serial_port->Exportable) $Doc->ExportCaption($this->serial_port);
					if ($this->baud_rate->Exportable) $Doc->ExportCaption($this->baud_rate);
					if ($this->ethernet_port->Exportable) $Doc->ExportCaption($this->ethernet_port);
					if ($this->layar->Exportable) $Doc->ExportCaption($this->layar);
					if ($this->alg_ver->Exportable) $Doc->ExportCaption($this->alg_ver);
					if ($this->use_realtime->Exportable) $Doc->ExportCaption($this->use_realtime);
					if ($this->group_realtime->Exportable) $Doc->ExportCaption($this->group_realtime);
					if ($this->last_download->Exportable) $Doc->ExportCaption($this->last_download);
					if ($this->ATTLOGStamp->Exportable) $Doc->ExportCaption($this->ATTLOGStamp);
					if ($this->OPERLOGStamp->Exportable) $Doc->ExportCaption($this->OPERLOGStamp);
					if ($this->ATTPHOTOStamp->Exportable) $Doc->ExportCaption($this->ATTPHOTOStamp);
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
						if ($this->sn->Exportable) $Doc->ExportField($this->sn);
						if ($this->activation_code->Exportable) $Doc->ExportField($this->activation_code);
						if ($this->act_code_realtime->Exportable) $Doc->ExportField($this->act_code_realtime);
						if ($this->device_name->Exportable) $Doc->ExportField($this->device_name);
						if ($this->comm_key->Exportable) $Doc->ExportField($this->comm_key);
						if ($this->dev_id->Exportable) $Doc->ExportField($this->dev_id);
						if ($this->comm_type->Exportable) $Doc->ExportField($this->comm_type);
						if ($this->ip_address->Exportable) $Doc->ExportField($this->ip_address);
						if ($this->id_type->Exportable) $Doc->ExportField($this->id_type);
						if ($this->dev_type->Exportable) $Doc->ExportField($this->dev_type);
						if ($this->serial_port->Exportable) $Doc->ExportField($this->serial_port);
						if ($this->baud_rate->Exportable) $Doc->ExportField($this->baud_rate);
						if ($this->ethernet_port->Exportable) $Doc->ExportField($this->ethernet_port);
						if ($this->layar->Exportable) $Doc->ExportField($this->layar);
						if ($this->alg_ver->Exportable) $Doc->ExportField($this->alg_ver);
						if ($this->use_realtime->Exportable) $Doc->ExportField($this->use_realtime);
						if ($this->group_realtime->Exportable) $Doc->ExportField($this->group_realtime);
						if ($this->last_download->Exportable) $Doc->ExportField($this->last_download);
						if ($this->ATTLOGStamp->Exportable) $Doc->ExportField($this->ATTLOGStamp);
						if ($this->OPERLOGStamp->Exportable) $Doc->ExportField($this->OPERLOGStamp);
						if ($this->ATTPHOTOStamp->Exportable) $Doc->ExportField($this->ATTPHOTOStamp);
					} else {
						if ($this->sn->Exportable) $Doc->ExportField($this->sn);
						if ($this->activation_code->Exportable) $Doc->ExportField($this->activation_code);
						if ($this->act_code_realtime->Exportable) $Doc->ExportField($this->act_code_realtime);
						if ($this->device_name->Exportable) $Doc->ExportField($this->device_name);
						if ($this->comm_key->Exportable) $Doc->ExportField($this->comm_key);
						if ($this->dev_id->Exportable) $Doc->ExportField($this->dev_id);
						if ($this->comm_type->Exportable) $Doc->ExportField($this->comm_type);
						if ($this->ip_address->Exportable) $Doc->ExportField($this->ip_address);
						if ($this->id_type->Exportable) $Doc->ExportField($this->id_type);
						if ($this->dev_type->Exportable) $Doc->ExportField($this->dev_type);
						if ($this->serial_port->Exportable) $Doc->ExportField($this->serial_port);
						if ($this->baud_rate->Exportable) $Doc->ExportField($this->baud_rate);
						if ($this->ethernet_port->Exportable) $Doc->ExportField($this->ethernet_port);
						if ($this->layar->Exportable) $Doc->ExportField($this->layar);
						if ($this->alg_ver->Exportable) $Doc->ExportField($this->alg_ver);
						if ($this->use_realtime->Exportable) $Doc->ExportField($this->use_realtime);
						if ($this->group_realtime->Exportable) $Doc->ExportField($this->group_realtime);
						if ($this->last_download->Exportable) $Doc->ExportField($this->last_download);
						if ($this->ATTLOGStamp->Exportable) $Doc->ExportField($this->ATTLOGStamp);
						if ($this->OPERLOGStamp->Exportable) $Doc->ExportField($this->OPERLOGStamp);
						if ($this->ATTPHOTOStamp->Exportable) $Doc->ExportField($this->ATTPHOTOStamp);
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
