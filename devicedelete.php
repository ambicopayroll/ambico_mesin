<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "deviceinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$device_delete = NULL; // Initialize page object first

class cdevice_delete extends cdevice {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{0B4A4F9E-7A2B-4234-9791-3975C1DCDDA1}";

	// Table name
	var $TableName = 'device';

	// Page object name
	var $PageObjName = 'device_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (device)
		if (!isset($GLOBALS["device"]) || get_class($GLOBALS["device"]) == "cdevice") {
			$GLOBALS["device"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["device"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'device', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (t_user)
		if (!isset($UserTable)) {
			$UserTable = new ct_user();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("devicelist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->sn->SetVisibility();
		$this->activation_code->SetVisibility();
		$this->act_code_realtime->SetVisibility();
		$this->device_name->SetVisibility();
		$this->comm_key->SetVisibility();
		$this->dev_id->SetVisibility();
		$this->comm_type->SetVisibility();
		$this->ip_address->SetVisibility();
		$this->id_type->SetVisibility();
		$this->dev_type->SetVisibility();
		$this->serial_port->SetVisibility();
		$this->baud_rate->SetVisibility();
		$this->ethernet_port->SetVisibility();
		$this->layar->SetVisibility();
		$this->alg_ver->SetVisibility();
		$this->use_realtime->SetVisibility();
		$this->group_realtime->SetVisibility();
		$this->last_download->SetVisibility();
		$this->ATTLOGStamp->SetVisibility();
		$this->OPERLOGStamp->SetVisibility();
		$this->ATTPHOTOStamp->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $device;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($device);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("devicelist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in device class, deviceinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("devicelist.php"); // Return to list
			}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
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

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->sn->DbValue = $row['sn'];
		$this->activation_code->DbValue = $row['activation_code'];
		$this->act_code_realtime->DbValue = $row['act_code_realtime'];
		$this->device_name->DbValue = $row['device_name'];
		$this->comm_key->DbValue = $row['comm_key'];
		$this->dev_id->DbValue = $row['dev_id'];
		$this->comm_type->DbValue = $row['comm_type'];
		$this->ip_address->DbValue = $row['ip_address'];
		$this->id_type->DbValue = $row['id_type'];
		$this->dev_type->DbValue = $row['dev_type'];
		$this->serial_port->DbValue = $row['serial_port'];
		$this->baud_rate->DbValue = $row['baud_rate'];
		$this->ethernet_port->DbValue = $row['ethernet_port'];
		$this->layar->DbValue = $row['layar'];
		$this->alg_ver->DbValue = $row['alg_ver'];
		$this->use_realtime->DbValue = $row['use_realtime'];
		$this->group_realtime->DbValue = $row['group_realtime'];
		$this->last_download->DbValue = $row['last_download'];
		$this->ATTLOGStamp->DbValue = $row['ATTLOGStamp'];
		$this->OPERLOGStamp->DbValue = $row['OPERLOGStamp'];
		$this->ATTPHOTOStamp->DbValue = $row['ATTPHOTOStamp'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['sn'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("devicelist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($device_delete)) $device_delete = new cdevice_delete();

// Page init
$device_delete->Page_Init();

// Page main
$device_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$device_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fdevicedelete = new ew_Form("fdevicedelete", "delete");

// Form_CustomValidate event
fdevicedelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdevicedelete.ValidateRequired = true;
<?php } else { ?>
fdevicedelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $device_delete->ShowPageHeader(); ?>
<?php
$device_delete->ShowMessage();
?>
<form name="fdevicedelete" id="fdevicedelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($device_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $device_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="device">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($device_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $device->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($device->sn->Visible) { // sn ?>
		<th><span id="elh_device_sn" class="device_sn"><?php echo $device->sn->FldCaption() ?></span></th>
<?php } ?>
<?php if ($device->activation_code->Visible) { // activation_code ?>
		<th><span id="elh_device_activation_code" class="device_activation_code"><?php echo $device->activation_code->FldCaption() ?></span></th>
<?php } ?>
<?php if ($device->act_code_realtime->Visible) { // act_code_realtime ?>
		<th><span id="elh_device_act_code_realtime" class="device_act_code_realtime"><?php echo $device->act_code_realtime->FldCaption() ?></span></th>
<?php } ?>
<?php if ($device->device_name->Visible) { // device_name ?>
		<th><span id="elh_device_device_name" class="device_device_name"><?php echo $device->device_name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($device->comm_key->Visible) { // comm_key ?>
		<th><span id="elh_device_comm_key" class="device_comm_key"><?php echo $device->comm_key->FldCaption() ?></span></th>
<?php } ?>
<?php if ($device->dev_id->Visible) { // dev_id ?>
		<th><span id="elh_device_dev_id" class="device_dev_id"><?php echo $device->dev_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($device->comm_type->Visible) { // comm_type ?>
		<th><span id="elh_device_comm_type" class="device_comm_type"><?php echo $device->comm_type->FldCaption() ?></span></th>
<?php } ?>
<?php if ($device->ip_address->Visible) { // ip_address ?>
		<th><span id="elh_device_ip_address" class="device_ip_address"><?php echo $device->ip_address->FldCaption() ?></span></th>
<?php } ?>
<?php if ($device->id_type->Visible) { // id_type ?>
		<th><span id="elh_device_id_type" class="device_id_type"><?php echo $device->id_type->FldCaption() ?></span></th>
<?php } ?>
<?php if ($device->dev_type->Visible) { // dev_type ?>
		<th><span id="elh_device_dev_type" class="device_dev_type"><?php echo $device->dev_type->FldCaption() ?></span></th>
<?php } ?>
<?php if ($device->serial_port->Visible) { // serial_port ?>
		<th><span id="elh_device_serial_port" class="device_serial_port"><?php echo $device->serial_port->FldCaption() ?></span></th>
<?php } ?>
<?php if ($device->baud_rate->Visible) { // baud_rate ?>
		<th><span id="elh_device_baud_rate" class="device_baud_rate"><?php echo $device->baud_rate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($device->ethernet_port->Visible) { // ethernet_port ?>
		<th><span id="elh_device_ethernet_port" class="device_ethernet_port"><?php echo $device->ethernet_port->FldCaption() ?></span></th>
<?php } ?>
<?php if ($device->layar->Visible) { // layar ?>
		<th><span id="elh_device_layar" class="device_layar"><?php echo $device->layar->FldCaption() ?></span></th>
<?php } ?>
<?php if ($device->alg_ver->Visible) { // alg_ver ?>
		<th><span id="elh_device_alg_ver" class="device_alg_ver"><?php echo $device->alg_ver->FldCaption() ?></span></th>
<?php } ?>
<?php if ($device->use_realtime->Visible) { // use_realtime ?>
		<th><span id="elh_device_use_realtime" class="device_use_realtime"><?php echo $device->use_realtime->FldCaption() ?></span></th>
<?php } ?>
<?php if ($device->group_realtime->Visible) { // group_realtime ?>
		<th><span id="elh_device_group_realtime" class="device_group_realtime"><?php echo $device->group_realtime->FldCaption() ?></span></th>
<?php } ?>
<?php if ($device->last_download->Visible) { // last_download ?>
		<th><span id="elh_device_last_download" class="device_last_download"><?php echo $device->last_download->FldCaption() ?></span></th>
<?php } ?>
<?php if ($device->ATTLOGStamp->Visible) { // ATTLOGStamp ?>
		<th><span id="elh_device_ATTLOGStamp" class="device_ATTLOGStamp"><?php echo $device->ATTLOGStamp->FldCaption() ?></span></th>
<?php } ?>
<?php if ($device->OPERLOGStamp->Visible) { // OPERLOGStamp ?>
		<th><span id="elh_device_OPERLOGStamp" class="device_OPERLOGStamp"><?php echo $device->OPERLOGStamp->FldCaption() ?></span></th>
<?php } ?>
<?php if ($device->ATTPHOTOStamp->Visible) { // ATTPHOTOStamp ?>
		<th><span id="elh_device_ATTPHOTOStamp" class="device_ATTPHOTOStamp"><?php echo $device->ATTPHOTOStamp->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$device_delete->RecCnt = 0;
$i = 0;
while (!$device_delete->Recordset->EOF) {
	$device_delete->RecCnt++;
	$device_delete->RowCnt++;

	// Set row properties
	$device->ResetAttrs();
	$device->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$device_delete->LoadRowValues($device_delete->Recordset);

	// Render row
	$device_delete->RenderRow();
?>
	<tr<?php echo $device->RowAttributes() ?>>
<?php if ($device->sn->Visible) { // sn ?>
		<td<?php echo $device->sn->CellAttributes() ?>>
<span id="el<?php echo $device_delete->RowCnt ?>_device_sn" class="device_sn">
<span<?php echo $device->sn->ViewAttributes() ?>>
<?php echo $device->sn->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($device->activation_code->Visible) { // activation_code ?>
		<td<?php echo $device->activation_code->CellAttributes() ?>>
<span id="el<?php echo $device_delete->RowCnt ?>_device_activation_code" class="device_activation_code">
<span<?php echo $device->activation_code->ViewAttributes() ?>>
<?php echo $device->activation_code->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($device->act_code_realtime->Visible) { // act_code_realtime ?>
		<td<?php echo $device->act_code_realtime->CellAttributes() ?>>
<span id="el<?php echo $device_delete->RowCnt ?>_device_act_code_realtime" class="device_act_code_realtime">
<span<?php echo $device->act_code_realtime->ViewAttributes() ?>>
<?php echo $device->act_code_realtime->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($device->device_name->Visible) { // device_name ?>
		<td<?php echo $device->device_name->CellAttributes() ?>>
<span id="el<?php echo $device_delete->RowCnt ?>_device_device_name" class="device_device_name">
<span<?php echo $device->device_name->ViewAttributes() ?>>
<?php echo $device->device_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($device->comm_key->Visible) { // comm_key ?>
		<td<?php echo $device->comm_key->CellAttributes() ?>>
<span id="el<?php echo $device_delete->RowCnt ?>_device_comm_key" class="device_comm_key">
<span<?php echo $device->comm_key->ViewAttributes() ?>>
<?php echo $device->comm_key->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($device->dev_id->Visible) { // dev_id ?>
		<td<?php echo $device->dev_id->CellAttributes() ?>>
<span id="el<?php echo $device_delete->RowCnt ?>_device_dev_id" class="device_dev_id">
<span<?php echo $device->dev_id->ViewAttributes() ?>>
<?php echo $device->dev_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($device->comm_type->Visible) { // comm_type ?>
		<td<?php echo $device->comm_type->CellAttributes() ?>>
<span id="el<?php echo $device_delete->RowCnt ?>_device_comm_type" class="device_comm_type">
<span<?php echo $device->comm_type->ViewAttributes() ?>>
<?php echo $device->comm_type->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($device->ip_address->Visible) { // ip_address ?>
		<td<?php echo $device->ip_address->CellAttributes() ?>>
<span id="el<?php echo $device_delete->RowCnt ?>_device_ip_address" class="device_ip_address">
<span<?php echo $device->ip_address->ViewAttributes() ?>>
<?php echo $device->ip_address->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($device->id_type->Visible) { // id_type ?>
		<td<?php echo $device->id_type->CellAttributes() ?>>
<span id="el<?php echo $device_delete->RowCnt ?>_device_id_type" class="device_id_type">
<span<?php echo $device->id_type->ViewAttributes() ?>>
<?php echo $device->id_type->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($device->dev_type->Visible) { // dev_type ?>
		<td<?php echo $device->dev_type->CellAttributes() ?>>
<span id="el<?php echo $device_delete->RowCnt ?>_device_dev_type" class="device_dev_type">
<span<?php echo $device->dev_type->ViewAttributes() ?>>
<?php echo $device->dev_type->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($device->serial_port->Visible) { // serial_port ?>
		<td<?php echo $device->serial_port->CellAttributes() ?>>
<span id="el<?php echo $device_delete->RowCnt ?>_device_serial_port" class="device_serial_port">
<span<?php echo $device->serial_port->ViewAttributes() ?>>
<?php echo $device->serial_port->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($device->baud_rate->Visible) { // baud_rate ?>
		<td<?php echo $device->baud_rate->CellAttributes() ?>>
<span id="el<?php echo $device_delete->RowCnt ?>_device_baud_rate" class="device_baud_rate">
<span<?php echo $device->baud_rate->ViewAttributes() ?>>
<?php echo $device->baud_rate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($device->ethernet_port->Visible) { // ethernet_port ?>
		<td<?php echo $device->ethernet_port->CellAttributes() ?>>
<span id="el<?php echo $device_delete->RowCnt ?>_device_ethernet_port" class="device_ethernet_port">
<span<?php echo $device->ethernet_port->ViewAttributes() ?>>
<?php echo $device->ethernet_port->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($device->layar->Visible) { // layar ?>
		<td<?php echo $device->layar->CellAttributes() ?>>
<span id="el<?php echo $device_delete->RowCnt ?>_device_layar" class="device_layar">
<span<?php echo $device->layar->ViewAttributes() ?>>
<?php echo $device->layar->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($device->alg_ver->Visible) { // alg_ver ?>
		<td<?php echo $device->alg_ver->CellAttributes() ?>>
<span id="el<?php echo $device_delete->RowCnt ?>_device_alg_ver" class="device_alg_ver">
<span<?php echo $device->alg_ver->ViewAttributes() ?>>
<?php echo $device->alg_ver->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($device->use_realtime->Visible) { // use_realtime ?>
		<td<?php echo $device->use_realtime->CellAttributes() ?>>
<span id="el<?php echo $device_delete->RowCnt ?>_device_use_realtime" class="device_use_realtime">
<span<?php echo $device->use_realtime->ViewAttributes() ?>>
<?php echo $device->use_realtime->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($device->group_realtime->Visible) { // group_realtime ?>
		<td<?php echo $device->group_realtime->CellAttributes() ?>>
<span id="el<?php echo $device_delete->RowCnt ?>_device_group_realtime" class="device_group_realtime">
<span<?php echo $device->group_realtime->ViewAttributes() ?>>
<?php echo $device->group_realtime->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($device->last_download->Visible) { // last_download ?>
		<td<?php echo $device->last_download->CellAttributes() ?>>
<span id="el<?php echo $device_delete->RowCnt ?>_device_last_download" class="device_last_download">
<span<?php echo $device->last_download->ViewAttributes() ?>>
<?php echo $device->last_download->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($device->ATTLOGStamp->Visible) { // ATTLOGStamp ?>
		<td<?php echo $device->ATTLOGStamp->CellAttributes() ?>>
<span id="el<?php echo $device_delete->RowCnt ?>_device_ATTLOGStamp" class="device_ATTLOGStamp">
<span<?php echo $device->ATTLOGStamp->ViewAttributes() ?>>
<?php echo $device->ATTLOGStamp->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($device->OPERLOGStamp->Visible) { // OPERLOGStamp ?>
		<td<?php echo $device->OPERLOGStamp->CellAttributes() ?>>
<span id="el<?php echo $device_delete->RowCnt ?>_device_OPERLOGStamp" class="device_OPERLOGStamp">
<span<?php echo $device->OPERLOGStamp->ViewAttributes() ?>>
<?php echo $device->OPERLOGStamp->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($device->ATTPHOTOStamp->Visible) { // ATTPHOTOStamp ?>
		<td<?php echo $device->ATTPHOTOStamp->CellAttributes() ?>>
<span id="el<?php echo $device_delete->RowCnt ?>_device_ATTPHOTOStamp" class="device_ATTPHOTOStamp">
<span<?php echo $device->ATTPHOTOStamp->ViewAttributes() ?>>
<?php echo $device->ATTPHOTOStamp->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$device_delete->Recordset->MoveNext();
}
$device_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $device_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fdevicedelete.Init();
</script>
<?php
$device_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$device_delete->Page_Terminate();
?>
