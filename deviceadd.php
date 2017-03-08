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

$device_add = NULL; // Initialize page object first

class cdevice_add extends cdevice {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{0B4A4F9E-7A2B-4234-9791-3975C1DCDDA1}";

	// Table name
	var $TableName = 'device';

	// Page object name
	var $PageObjName = 'device_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
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

		// Create form object
		$objForm = new cFormObj();
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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
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

			// Handle modal response
			if ($this->IsModal) {
				$row = array();
				$row["url"] = $url;
				echo ew_ArrayToJson(array($row));
			} else {
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["sn"] != "") {
				$this->sn->setQueryStringValue($_GET["sn"]);
				$this->setKey("sn", $this->sn->CurrentValue); // Set up key
			} else {
				$this->setKey("sn", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("devicelist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "devicelist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "deviceview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->sn->CurrentValue = NULL;
		$this->sn->OldValue = $this->sn->CurrentValue;
		$this->activation_code->CurrentValue = NULL;
		$this->activation_code->OldValue = $this->activation_code->CurrentValue;
		$this->act_code_realtime->CurrentValue = NULL;
		$this->act_code_realtime->OldValue = $this->act_code_realtime->CurrentValue;
		$this->device_name->CurrentValue = NULL;
		$this->device_name->OldValue = $this->device_name->CurrentValue;
		$this->comm_key->CurrentValue = 0;
		$this->dev_id->CurrentValue = 1;
		$this->comm_type->CurrentValue = 0;
		$this->ip_address->CurrentValue = NULL;
		$this->ip_address->OldValue = $this->ip_address->CurrentValue;
		$this->id_type->CurrentValue = 0;
		$this->dev_type->CurrentValue = 0;
		$this->serial_port->CurrentValue = NULL;
		$this->serial_port->OldValue = $this->serial_port->CurrentValue;
		$this->baud_rate->CurrentValue = NULL;
		$this->baud_rate->OldValue = $this->baud_rate->CurrentValue;
		$this->ethernet_port->CurrentValue = "4370";
		$this->layar->CurrentValue = 0;
		$this->alg_ver->CurrentValue = 10;
		$this->use_realtime->CurrentValue = 0;
		$this->group_realtime->CurrentValue = 0;
		$this->last_download->CurrentValue = NULL;
		$this->last_download->OldValue = $this->last_download->CurrentValue;
		$this->ATTLOGStamp->CurrentValue = "0";
		$this->OPERLOGStamp->CurrentValue = "0";
		$this->ATTPHOTOStamp->CurrentValue = "0";
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->sn->FldIsDetailKey) {
			$this->sn->setFormValue($objForm->GetValue("x_sn"));
		}
		if (!$this->activation_code->FldIsDetailKey) {
			$this->activation_code->setFormValue($objForm->GetValue("x_activation_code"));
		}
		if (!$this->act_code_realtime->FldIsDetailKey) {
			$this->act_code_realtime->setFormValue($objForm->GetValue("x_act_code_realtime"));
		}
		if (!$this->device_name->FldIsDetailKey) {
			$this->device_name->setFormValue($objForm->GetValue("x_device_name"));
		}
		if (!$this->comm_key->FldIsDetailKey) {
			$this->comm_key->setFormValue($objForm->GetValue("x_comm_key"));
		}
		if (!$this->dev_id->FldIsDetailKey) {
			$this->dev_id->setFormValue($objForm->GetValue("x_dev_id"));
		}
		if (!$this->comm_type->FldIsDetailKey) {
			$this->comm_type->setFormValue($objForm->GetValue("x_comm_type"));
		}
		if (!$this->ip_address->FldIsDetailKey) {
			$this->ip_address->setFormValue($objForm->GetValue("x_ip_address"));
		}
		if (!$this->id_type->FldIsDetailKey) {
			$this->id_type->setFormValue($objForm->GetValue("x_id_type"));
		}
		if (!$this->dev_type->FldIsDetailKey) {
			$this->dev_type->setFormValue($objForm->GetValue("x_dev_type"));
		}
		if (!$this->serial_port->FldIsDetailKey) {
			$this->serial_port->setFormValue($objForm->GetValue("x_serial_port"));
		}
		if (!$this->baud_rate->FldIsDetailKey) {
			$this->baud_rate->setFormValue($objForm->GetValue("x_baud_rate"));
		}
		if (!$this->ethernet_port->FldIsDetailKey) {
			$this->ethernet_port->setFormValue($objForm->GetValue("x_ethernet_port"));
		}
		if (!$this->layar->FldIsDetailKey) {
			$this->layar->setFormValue($objForm->GetValue("x_layar"));
		}
		if (!$this->alg_ver->FldIsDetailKey) {
			$this->alg_ver->setFormValue($objForm->GetValue("x_alg_ver"));
		}
		if (!$this->use_realtime->FldIsDetailKey) {
			$this->use_realtime->setFormValue($objForm->GetValue("x_use_realtime"));
		}
		if (!$this->group_realtime->FldIsDetailKey) {
			$this->group_realtime->setFormValue($objForm->GetValue("x_group_realtime"));
		}
		if (!$this->last_download->FldIsDetailKey) {
			$this->last_download->setFormValue($objForm->GetValue("x_last_download"));
			$this->last_download->CurrentValue = ew_UnFormatDateTime($this->last_download->CurrentValue, 0);
		}
		if (!$this->ATTLOGStamp->FldIsDetailKey) {
			$this->ATTLOGStamp->setFormValue($objForm->GetValue("x_ATTLOGStamp"));
		}
		if (!$this->OPERLOGStamp->FldIsDetailKey) {
			$this->OPERLOGStamp->setFormValue($objForm->GetValue("x_OPERLOGStamp"));
		}
		if (!$this->ATTPHOTOStamp->FldIsDetailKey) {
			$this->ATTPHOTOStamp->setFormValue($objForm->GetValue("x_ATTPHOTOStamp"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->sn->CurrentValue = $this->sn->FormValue;
		$this->activation_code->CurrentValue = $this->activation_code->FormValue;
		$this->act_code_realtime->CurrentValue = $this->act_code_realtime->FormValue;
		$this->device_name->CurrentValue = $this->device_name->FormValue;
		$this->comm_key->CurrentValue = $this->comm_key->FormValue;
		$this->dev_id->CurrentValue = $this->dev_id->FormValue;
		$this->comm_type->CurrentValue = $this->comm_type->FormValue;
		$this->ip_address->CurrentValue = $this->ip_address->FormValue;
		$this->id_type->CurrentValue = $this->id_type->FormValue;
		$this->dev_type->CurrentValue = $this->dev_type->FormValue;
		$this->serial_port->CurrentValue = $this->serial_port->FormValue;
		$this->baud_rate->CurrentValue = $this->baud_rate->FormValue;
		$this->ethernet_port->CurrentValue = $this->ethernet_port->FormValue;
		$this->layar->CurrentValue = $this->layar->FormValue;
		$this->alg_ver->CurrentValue = $this->alg_ver->FormValue;
		$this->use_realtime->CurrentValue = $this->use_realtime->FormValue;
		$this->group_realtime->CurrentValue = $this->group_realtime->FormValue;
		$this->last_download->CurrentValue = $this->last_download->FormValue;
		$this->last_download->CurrentValue = ew_UnFormatDateTime($this->last_download->CurrentValue, 0);
		$this->ATTLOGStamp->CurrentValue = $this->ATTLOGStamp->FormValue;
		$this->OPERLOGStamp->CurrentValue = $this->OPERLOGStamp->FormValue;
		$this->ATTPHOTOStamp->CurrentValue = $this->ATTPHOTOStamp->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("sn")) <> "")
			$this->sn->CurrentValue = $this->getKey("sn"); // sn
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// sn
			$this->sn->EditAttrs["class"] = "form-control";
			$this->sn->EditCustomAttributes = "";
			$this->sn->EditValue = ew_HtmlEncode($this->sn->CurrentValue);
			$this->sn->PlaceHolder = ew_RemoveHtml($this->sn->FldCaption());

			// activation_code
			$this->activation_code->EditAttrs["class"] = "form-control";
			$this->activation_code->EditCustomAttributes = "";
			$this->activation_code->EditValue = ew_HtmlEncode($this->activation_code->CurrentValue);
			$this->activation_code->PlaceHolder = ew_RemoveHtml($this->activation_code->FldCaption());

			// act_code_realtime
			$this->act_code_realtime->EditAttrs["class"] = "form-control";
			$this->act_code_realtime->EditCustomAttributes = "";
			$this->act_code_realtime->EditValue = ew_HtmlEncode($this->act_code_realtime->CurrentValue);
			$this->act_code_realtime->PlaceHolder = ew_RemoveHtml($this->act_code_realtime->FldCaption());

			// device_name
			$this->device_name->EditAttrs["class"] = "form-control";
			$this->device_name->EditCustomAttributes = "";
			$this->device_name->EditValue = ew_HtmlEncode($this->device_name->CurrentValue);
			$this->device_name->PlaceHolder = ew_RemoveHtml($this->device_name->FldCaption());

			// comm_key
			$this->comm_key->EditAttrs["class"] = "form-control";
			$this->comm_key->EditCustomAttributes = "";
			$this->comm_key->EditValue = ew_HtmlEncode($this->comm_key->CurrentValue);
			$this->comm_key->PlaceHolder = ew_RemoveHtml($this->comm_key->FldCaption());

			// dev_id
			$this->dev_id->EditAttrs["class"] = "form-control";
			$this->dev_id->EditCustomAttributes = "";
			$this->dev_id->EditValue = ew_HtmlEncode($this->dev_id->CurrentValue);
			$this->dev_id->PlaceHolder = ew_RemoveHtml($this->dev_id->FldCaption());

			// comm_type
			$this->comm_type->EditAttrs["class"] = "form-control";
			$this->comm_type->EditCustomAttributes = "";
			$this->comm_type->EditValue = ew_HtmlEncode($this->comm_type->CurrentValue);
			$this->comm_type->PlaceHolder = ew_RemoveHtml($this->comm_type->FldCaption());

			// ip_address
			$this->ip_address->EditAttrs["class"] = "form-control";
			$this->ip_address->EditCustomAttributes = "";
			$this->ip_address->EditValue = ew_HtmlEncode($this->ip_address->CurrentValue);
			$this->ip_address->PlaceHolder = ew_RemoveHtml($this->ip_address->FldCaption());

			// id_type
			$this->id_type->EditAttrs["class"] = "form-control";
			$this->id_type->EditCustomAttributes = "";
			$this->id_type->EditValue = ew_HtmlEncode($this->id_type->CurrentValue);
			$this->id_type->PlaceHolder = ew_RemoveHtml($this->id_type->FldCaption());

			// dev_type
			$this->dev_type->EditAttrs["class"] = "form-control";
			$this->dev_type->EditCustomAttributes = "";
			$this->dev_type->EditValue = ew_HtmlEncode($this->dev_type->CurrentValue);
			$this->dev_type->PlaceHolder = ew_RemoveHtml($this->dev_type->FldCaption());

			// serial_port
			$this->serial_port->EditAttrs["class"] = "form-control";
			$this->serial_port->EditCustomAttributes = "";
			$this->serial_port->EditValue = ew_HtmlEncode($this->serial_port->CurrentValue);
			$this->serial_port->PlaceHolder = ew_RemoveHtml($this->serial_port->FldCaption());

			// baud_rate
			$this->baud_rate->EditAttrs["class"] = "form-control";
			$this->baud_rate->EditCustomAttributes = "";
			$this->baud_rate->EditValue = ew_HtmlEncode($this->baud_rate->CurrentValue);
			$this->baud_rate->PlaceHolder = ew_RemoveHtml($this->baud_rate->FldCaption());

			// ethernet_port
			$this->ethernet_port->EditAttrs["class"] = "form-control";
			$this->ethernet_port->EditCustomAttributes = "";
			$this->ethernet_port->EditValue = ew_HtmlEncode($this->ethernet_port->CurrentValue);
			$this->ethernet_port->PlaceHolder = ew_RemoveHtml($this->ethernet_port->FldCaption());

			// layar
			$this->layar->EditAttrs["class"] = "form-control";
			$this->layar->EditCustomAttributes = "";
			$this->layar->EditValue = ew_HtmlEncode($this->layar->CurrentValue);
			$this->layar->PlaceHolder = ew_RemoveHtml($this->layar->FldCaption());

			// alg_ver
			$this->alg_ver->EditAttrs["class"] = "form-control";
			$this->alg_ver->EditCustomAttributes = "";
			$this->alg_ver->EditValue = ew_HtmlEncode($this->alg_ver->CurrentValue);
			$this->alg_ver->PlaceHolder = ew_RemoveHtml($this->alg_ver->FldCaption());

			// use_realtime
			$this->use_realtime->EditAttrs["class"] = "form-control";
			$this->use_realtime->EditCustomAttributes = "";
			$this->use_realtime->EditValue = ew_HtmlEncode($this->use_realtime->CurrentValue);
			$this->use_realtime->PlaceHolder = ew_RemoveHtml($this->use_realtime->FldCaption());

			// group_realtime
			$this->group_realtime->EditAttrs["class"] = "form-control";
			$this->group_realtime->EditCustomAttributes = "";
			$this->group_realtime->EditValue = ew_HtmlEncode($this->group_realtime->CurrentValue);
			$this->group_realtime->PlaceHolder = ew_RemoveHtml($this->group_realtime->FldCaption());

			// last_download
			$this->last_download->EditAttrs["class"] = "form-control";
			$this->last_download->EditCustomAttributes = "";
			$this->last_download->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->last_download->CurrentValue, 8));
			$this->last_download->PlaceHolder = ew_RemoveHtml($this->last_download->FldCaption());

			// ATTLOGStamp
			$this->ATTLOGStamp->EditAttrs["class"] = "form-control";
			$this->ATTLOGStamp->EditCustomAttributes = "";
			$this->ATTLOGStamp->EditValue = ew_HtmlEncode($this->ATTLOGStamp->CurrentValue);
			$this->ATTLOGStamp->PlaceHolder = ew_RemoveHtml($this->ATTLOGStamp->FldCaption());

			// OPERLOGStamp
			$this->OPERLOGStamp->EditAttrs["class"] = "form-control";
			$this->OPERLOGStamp->EditCustomAttributes = "";
			$this->OPERLOGStamp->EditValue = ew_HtmlEncode($this->OPERLOGStamp->CurrentValue);
			$this->OPERLOGStamp->PlaceHolder = ew_RemoveHtml($this->OPERLOGStamp->FldCaption());

			// ATTPHOTOStamp
			$this->ATTPHOTOStamp->EditAttrs["class"] = "form-control";
			$this->ATTPHOTOStamp->EditCustomAttributes = "";
			$this->ATTPHOTOStamp->EditValue = ew_HtmlEncode($this->ATTPHOTOStamp->CurrentValue);
			$this->ATTPHOTOStamp->PlaceHolder = ew_RemoveHtml($this->ATTPHOTOStamp->FldCaption());

			// Add refer script
			// sn

			$this->sn->LinkCustomAttributes = "";
			$this->sn->HrefValue = "";

			// activation_code
			$this->activation_code->LinkCustomAttributes = "";
			$this->activation_code->HrefValue = "";

			// act_code_realtime
			$this->act_code_realtime->LinkCustomAttributes = "";
			$this->act_code_realtime->HrefValue = "";

			// device_name
			$this->device_name->LinkCustomAttributes = "";
			$this->device_name->HrefValue = "";

			// comm_key
			$this->comm_key->LinkCustomAttributes = "";
			$this->comm_key->HrefValue = "";

			// dev_id
			$this->dev_id->LinkCustomAttributes = "";
			$this->dev_id->HrefValue = "";

			// comm_type
			$this->comm_type->LinkCustomAttributes = "";
			$this->comm_type->HrefValue = "";

			// ip_address
			$this->ip_address->LinkCustomAttributes = "";
			$this->ip_address->HrefValue = "";

			// id_type
			$this->id_type->LinkCustomAttributes = "";
			$this->id_type->HrefValue = "";

			// dev_type
			$this->dev_type->LinkCustomAttributes = "";
			$this->dev_type->HrefValue = "";

			// serial_port
			$this->serial_port->LinkCustomAttributes = "";
			$this->serial_port->HrefValue = "";

			// baud_rate
			$this->baud_rate->LinkCustomAttributes = "";
			$this->baud_rate->HrefValue = "";

			// ethernet_port
			$this->ethernet_port->LinkCustomAttributes = "";
			$this->ethernet_port->HrefValue = "";

			// layar
			$this->layar->LinkCustomAttributes = "";
			$this->layar->HrefValue = "";

			// alg_ver
			$this->alg_ver->LinkCustomAttributes = "";
			$this->alg_ver->HrefValue = "";

			// use_realtime
			$this->use_realtime->LinkCustomAttributes = "";
			$this->use_realtime->HrefValue = "";

			// group_realtime
			$this->group_realtime->LinkCustomAttributes = "";
			$this->group_realtime->HrefValue = "";

			// last_download
			$this->last_download->LinkCustomAttributes = "";
			$this->last_download->HrefValue = "";

			// ATTLOGStamp
			$this->ATTLOGStamp->LinkCustomAttributes = "";
			$this->ATTLOGStamp->HrefValue = "";

			// OPERLOGStamp
			$this->OPERLOGStamp->LinkCustomAttributes = "";
			$this->OPERLOGStamp->HrefValue = "";

			// ATTPHOTOStamp
			$this->ATTPHOTOStamp->LinkCustomAttributes = "";
			$this->ATTPHOTOStamp->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->sn->FldIsDetailKey && !is_null($this->sn->FormValue) && $this->sn->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->sn->FldCaption(), $this->sn->ReqErrMsg));
		}
		if (!$this->activation_code->FldIsDetailKey && !is_null($this->activation_code->FormValue) && $this->activation_code->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->activation_code->FldCaption(), $this->activation_code->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->comm_key->FormValue)) {
			ew_AddMessage($gsFormError, $this->comm_key->FldErrMsg());
		}
		if (!$this->dev_id->FldIsDetailKey && !is_null($this->dev_id->FormValue) && $this->dev_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->dev_id->FldCaption(), $this->dev_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->dev_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->dev_id->FldErrMsg());
		}
		if (!$this->comm_type->FldIsDetailKey && !is_null($this->comm_type->FormValue) && $this->comm_type->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->comm_type->FldCaption(), $this->comm_type->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->comm_type->FormValue)) {
			ew_AddMessage($gsFormError, $this->comm_type->FldErrMsg());
		}
		if (!$this->id_type->FldIsDetailKey && !is_null($this->id_type->FormValue) && $this->id_type->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_type->FldCaption(), $this->id_type->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->id_type->FormValue)) {
			ew_AddMessage($gsFormError, $this->id_type->FldErrMsg());
		}
		if (!$this->dev_type->FldIsDetailKey && !is_null($this->dev_type->FormValue) && $this->dev_type->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->dev_type->FldCaption(), $this->dev_type->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->dev_type->FormValue)) {
			ew_AddMessage($gsFormError, $this->dev_type->FldErrMsg());
		}
		if (!$this->ethernet_port->FldIsDetailKey && !is_null($this->ethernet_port->FormValue) && $this->ethernet_port->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ethernet_port->FldCaption(), $this->ethernet_port->ReqErrMsg));
		}
		if (!$this->layar->FldIsDetailKey && !is_null($this->layar->FormValue) && $this->layar->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->layar->FldCaption(), $this->layar->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->layar->FormValue)) {
			ew_AddMessage($gsFormError, $this->layar->FldErrMsg());
		}
		if (!$this->alg_ver->FldIsDetailKey && !is_null($this->alg_ver->FormValue) && $this->alg_ver->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->alg_ver->FldCaption(), $this->alg_ver->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->alg_ver->FormValue)) {
			ew_AddMessage($gsFormError, $this->alg_ver->FldErrMsg());
		}
		if (!$this->use_realtime->FldIsDetailKey && !is_null($this->use_realtime->FormValue) && $this->use_realtime->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->use_realtime->FldCaption(), $this->use_realtime->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->use_realtime->FormValue)) {
			ew_AddMessage($gsFormError, $this->use_realtime->FldErrMsg());
		}
		if (!$this->group_realtime->FldIsDetailKey && !is_null($this->group_realtime->FormValue) && $this->group_realtime->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->group_realtime->FldCaption(), $this->group_realtime->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->group_realtime->FormValue)) {
			ew_AddMessage($gsFormError, $this->group_realtime->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->last_download->FormValue)) {
			ew_AddMessage($gsFormError, $this->last_download->FldErrMsg());
		}
		if (!$this->ATTLOGStamp->FldIsDetailKey && !is_null($this->ATTLOGStamp->FormValue) && $this->ATTLOGStamp->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ATTLOGStamp->FldCaption(), $this->ATTLOGStamp->ReqErrMsg));
		}
		if (!$this->OPERLOGStamp->FldIsDetailKey && !is_null($this->OPERLOGStamp->FormValue) && $this->OPERLOGStamp->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->OPERLOGStamp->FldCaption(), $this->OPERLOGStamp->ReqErrMsg));
		}
		if (!$this->ATTPHOTOStamp->FldIsDetailKey && !is_null($this->ATTPHOTOStamp->FormValue) && $this->ATTPHOTOStamp->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ATTPHOTOStamp->FldCaption(), $this->ATTPHOTOStamp->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// sn
		$this->sn->SetDbValueDef($rsnew, $this->sn->CurrentValue, "", FALSE);

		// activation_code
		$this->activation_code->SetDbValueDef($rsnew, $this->activation_code->CurrentValue, "", FALSE);

		// act_code_realtime
		$this->act_code_realtime->SetDbValueDef($rsnew, $this->act_code_realtime->CurrentValue, NULL, FALSE);

		// device_name
		$this->device_name->SetDbValueDef($rsnew, $this->device_name->CurrentValue, NULL, FALSE);

		// comm_key
		$this->comm_key->SetDbValueDef($rsnew, $this->comm_key->CurrentValue, NULL, strval($this->comm_key->CurrentValue) == "");

		// dev_id
		$this->dev_id->SetDbValueDef($rsnew, $this->dev_id->CurrentValue, 0, strval($this->dev_id->CurrentValue) == "");

		// comm_type
		$this->comm_type->SetDbValueDef($rsnew, $this->comm_type->CurrentValue, 0, strval($this->comm_type->CurrentValue) == "");

		// ip_address
		$this->ip_address->SetDbValueDef($rsnew, $this->ip_address->CurrentValue, NULL, FALSE);

		// id_type
		$this->id_type->SetDbValueDef($rsnew, $this->id_type->CurrentValue, 0, strval($this->id_type->CurrentValue) == "");

		// dev_type
		$this->dev_type->SetDbValueDef($rsnew, $this->dev_type->CurrentValue, 0, strval($this->dev_type->CurrentValue) == "");

		// serial_port
		$this->serial_port->SetDbValueDef($rsnew, $this->serial_port->CurrentValue, NULL, FALSE);

		// baud_rate
		$this->baud_rate->SetDbValueDef($rsnew, $this->baud_rate->CurrentValue, NULL, FALSE);

		// ethernet_port
		$this->ethernet_port->SetDbValueDef($rsnew, $this->ethernet_port->CurrentValue, "", strval($this->ethernet_port->CurrentValue) == "");

		// layar
		$this->layar->SetDbValueDef($rsnew, $this->layar->CurrentValue, 0, strval($this->layar->CurrentValue) == "");

		// alg_ver
		$this->alg_ver->SetDbValueDef($rsnew, $this->alg_ver->CurrentValue, 0, strval($this->alg_ver->CurrentValue) == "");

		// use_realtime
		$this->use_realtime->SetDbValueDef($rsnew, $this->use_realtime->CurrentValue, 0, strval($this->use_realtime->CurrentValue) == "");

		// group_realtime
		$this->group_realtime->SetDbValueDef($rsnew, $this->group_realtime->CurrentValue, 0, strval($this->group_realtime->CurrentValue) == "");

		// last_download
		$this->last_download->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->last_download->CurrentValue, 0), NULL, FALSE);

		// ATTLOGStamp
		$this->ATTLOGStamp->SetDbValueDef($rsnew, $this->ATTLOGStamp->CurrentValue, "", strval($this->ATTLOGStamp->CurrentValue) == "");

		// OPERLOGStamp
		$this->OPERLOGStamp->SetDbValueDef($rsnew, $this->OPERLOGStamp->CurrentValue, "", strval($this->OPERLOGStamp->CurrentValue) == "");

		// ATTPHOTOStamp
		$this->ATTPHOTOStamp->SetDbValueDef($rsnew, $this->ATTPHOTOStamp->CurrentValue, "", strval($this->ATTPHOTOStamp->CurrentValue) == "");

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['sn']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check for duplicate key
		if ($bInsertRow && $this->ValidateKey) {
			$sFilter = $this->KeyFilter();
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sKeyErrMsg = str_replace("%f", $sFilter, $Language->Phrase("DupKey"));
				$this->setFailureMessage($sKeyErrMsg);
				$rsChk->Close();
				$bInsertRow = FALSE;
			}
		}
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("devicelist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($device_add)) $device_add = new cdevice_add();

// Page init
$device_add->Page_Init();

// Page main
$device_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$device_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fdeviceadd = new ew_Form("fdeviceadd", "add");

// Validate form
fdeviceadd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_sn");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $device->sn->FldCaption(), $device->sn->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_activation_code");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $device->activation_code->FldCaption(), $device->activation_code->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_comm_key");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($device->comm_key->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dev_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $device->dev_id->FldCaption(), $device->dev_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dev_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($device->dev_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_comm_type");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $device->comm_type->FldCaption(), $device->comm_type->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_comm_type");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($device->comm_type->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id_type");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $device->id_type->FldCaption(), $device->id_type->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_type");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($device->id_type->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dev_type");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $device->dev_type->FldCaption(), $device->dev_type->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dev_type");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($device->dev_type->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ethernet_port");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $device->ethernet_port->FldCaption(), $device->ethernet_port->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_layar");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $device->layar->FldCaption(), $device->layar->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_layar");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($device->layar->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_alg_ver");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $device->alg_ver->FldCaption(), $device->alg_ver->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_alg_ver");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($device->alg_ver->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_use_realtime");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $device->use_realtime->FldCaption(), $device->use_realtime->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_use_realtime");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($device->use_realtime->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_group_realtime");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $device->group_realtime->FldCaption(), $device->group_realtime->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_group_realtime");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($device->group_realtime->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_last_download");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($device->last_download->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ATTLOGStamp");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $device->ATTLOGStamp->FldCaption(), $device->ATTLOGStamp->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_OPERLOGStamp");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $device->OPERLOGStamp->FldCaption(), $device->OPERLOGStamp->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ATTPHOTOStamp");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $device->ATTPHOTOStamp->FldCaption(), $device->ATTPHOTOStamp->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fdeviceadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdeviceadd.ValidateRequired = true;
<?php } else { ?>
fdeviceadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$device_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $device_add->ShowPageHeader(); ?>
<?php
$device_add->ShowMessage();
?>
<form name="fdeviceadd" id="fdeviceadd" class="<?php echo $device_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($device_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $device_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="device">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($device_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($device->sn->Visible) { // sn ?>
	<div id="r_sn" class="form-group">
		<label id="elh_device_sn" for="x_sn" class="col-sm-2 control-label ewLabel"><?php echo $device->sn->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $device->sn->CellAttributes() ?>>
<span id="el_device_sn">
<input type="text" data-table="device" data-field="x_sn" name="x_sn" id="x_sn" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($device->sn->getPlaceHolder()) ?>" value="<?php echo $device->sn->EditValue ?>"<?php echo $device->sn->EditAttributes() ?>>
</span>
<?php echo $device->sn->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($device->activation_code->Visible) { // activation_code ?>
	<div id="r_activation_code" class="form-group">
		<label id="elh_device_activation_code" for="x_activation_code" class="col-sm-2 control-label ewLabel"><?php echo $device->activation_code->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $device->activation_code->CellAttributes() ?>>
<span id="el_device_activation_code">
<input type="text" data-table="device" data-field="x_activation_code" name="x_activation_code" id="x_activation_code" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($device->activation_code->getPlaceHolder()) ?>" value="<?php echo $device->activation_code->EditValue ?>"<?php echo $device->activation_code->EditAttributes() ?>>
</span>
<?php echo $device->activation_code->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($device->act_code_realtime->Visible) { // act_code_realtime ?>
	<div id="r_act_code_realtime" class="form-group">
		<label id="elh_device_act_code_realtime" for="x_act_code_realtime" class="col-sm-2 control-label ewLabel"><?php echo $device->act_code_realtime->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $device->act_code_realtime->CellAttributes() ?>>
<span id="el_device_act_code_realtime">
<input type="text" data-table="device" data-field="x_act_code_realtime" name="x_act_code_realtime" id="x_act_code_realtime" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($device->act_code_realtime->getPlaceHolder()) ?>" value="<?php echo $device->act_code_realtime->EditValue ?>"<?php echo $device->act_code_realtime->EditAttributes() ?>>
</span>
<?php echo $device->act_code_realtime->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($device->device_name->Visible) { // device_name ?>
	<div id="r_device_name" class="form-group">
		<label id="elh_device_device_name" for="x_device_name" class="col-sm-2 control-label ewLabel"><?php echo $device->device_name->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $device->device_name->CellAttributes() ?>>
<span id="el_device_device_name">
<input type="text" data-table="device" data-field="x_device_name" name="x_device_name" id="x_device_name" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($device->device_name->getPlaceHolder()) ?>" value="<?php echo $device->device_name->EditValue ?>"<?php echo $device->device_name->EditAttributes() ?>>
</span>
<?php echo $device->device_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($device->comm_key->Visible) { // comm_key ?>
	<div id="r_comm_key" class="form-group">
		<label id="elh_device_comm_key" for="x_comm_key" class="col-sm-2 control-label ewLabel"><?php echo $device->comm_key->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $device->comm_key->CellAttributes() ?>>
<span id="el_device_comm_key">
<input type="text" data-table="device" data-field="x_comm_key" name="x_comm_key" id="x_comm_key" size="30" placeholder="<?php echo ew_HtmlEncode($device->comm_key->getPlaceHolder()) ?>" value="<?php echo $device->comm_key->EditValue ?>"<?php echo $device->comm_key->EditAttributes() ?>>
</span>
<?php echo $device->comm_key->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($device->dev_id->Visible) { // dev_id ?>
	<div id="r_dev_id" class="form-group">
		<label id="elh_device_dev_id" for="x_dev_id" class="col-sm-2 control-label ewLabel"><?php echo $device->dev_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $device->dev_id->CellAttributes() ?>>
<span id="el_device_dev_id">
<input type="text" data-table="device" data-field="x_dev_id" name="x_dev_id" id="x_dev_id" size="30" placeholder="<?php echo ew_HtmlEncode($device->dev_id->getPlaceHolder()) ?>" value="<?php echo $device->dev_id->EditValue ?>"<?php echo $device->dev_id->EditAttributes() ?>>
</span>
<?php echo $device->dev_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($device->comm_type->Visible) { // comm_type ?>
	<div id="r_comm_type" class="form-group">
		<label id="elh_device_comm_type" for="x_comm_type" class="col-sm-2 control-label ewLabel"><?php echo $device->comm_type->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $device->comm_type->CellAttributes() ?>>
<span id="el_device_comm_type">
<input type="text" data-table="device" data-field="x_comm_type" name="x_comm_type" id="x_comm_type" size="30" placeholder="<?php echo ew_HtmlEncode($device->comm_type->getPlaceHolder()) ?>" value="<?php echo $device->comm_type->EditValue ?>"<?php echo $device->comm_type->EditAttributes() ?>>
</span>
<?php echo $device->comm_type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($device->ip_address->Visible) { // ip_address ?>
	<div id="r_ip_address" class="form-group">
		<label id="elh_device_ip_address" for="x_ip_address" class="col-sm-2 control-label ewLabel"><?php echo $device->ip_address->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $device->ip_address->CellAttributes() ?>>
<span id="el_device_ip_address">
<input type="text" data-table="device" data-field="x_ip_address" name="x_ip_address" id="x_ip_address" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($device->ip_address->getPlaceHolder()) ?>" value="<?php echo $device->ip_address->EditValue ?>"<?php echo $device->ip_address->EditAttributes() ?>>
</span>
<?php echo $device->ip_address->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($device->id_type->Visible) { // id_type ?>
	<div id="r_id_type" class="form-group">
		<label id="elh_device_id_type" for="x_id_type" class="col-sm-2 control-label ewLabel"><?php echo $device->id_type->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $device->id_type->CellAttributes() ?>>
<span id="el_device_id_type">
<input type="text" data-table="device" data-field="x_id_type" name="x_id_type" id="x_id_type" size="30" placeholder="<?php echo ew_HtmlEncode($device->id_type->getPlaceHolder()) ?>" value="<?php echo $device->id_type->EditValue ?>"<?php echo $device->id_type->EditAttributes() ?>>
</span>
<?php echo $device->id_type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($device->dev_type->Visible) { // dev_type ?>
	<div id="r_dev_type" class="form-group">
		<label id="elh_device_dev_type" for="x_dev_type" class="col-sm-2 control-label ewLabel"><?php echo $device->dev_type->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $device->dev_type->CellAttributes() ?>>
<span id="el_device_dev_type">
<input type="text" data-table="device" data-field="x_dev_type" name="x_dev_type" id="x_dev_type" size="30" placeholder="<?php echo ew_HtmlEncode($device->dev_type->getPlaceHolder()) ?>" value="<?php echo $device->dev_type->EditValue ?>"<?php echo $device->dev_type->EditAttributes() ?>>
</span>
<?php echo $device->dev_type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($device->serial_port->Visible) { // serial_port ?>
	<div id="r_serial_port" class="form-group">
		<label id="elh_device_serial_port" for="x_serial_port" class="col-sm-2 control-label ewLabel"><?php echo $device->serial_port->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $device->serial_port->CellAttributes() ?>>
<span id="el_device_serial_port">
<input type="text" data-table="device" data-field="x_serial_port" name="x_serial_port" id="x_serial_port" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($device->serial_port->getPlaceHolder()) ?>" value="<?php echo $device->serial_port->EditValue ?>"<?php echo $device->serial_port->EditAttributes() ?>>
</span>
<?php echo $device->serial_port->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($device->baud_rate->Visible) { // baud_rate ?>
	<div id="r_baud_rate" class="form-group">
		<label id="elh_device_baud_rate" for="x_baud_rate" class="col-sm-2 control-label ewLabel"><?php echo $device->baud_rate->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $device->baud_rate->CellAttributes() ?>>
<span id="el_device_baud_rate">
<input type="text" data-table="device" data-field="x_baud_rate" name="x_baud_rate" id="x_baud_rate" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($device->baud_rate->getPlaceHolder()) ?>" value="<?php echo $device->baud_rate->EditValue ?>"<?php echo $device->baud_rate->EditAttributes() ?>>
</span>
<?php echo $device->baud_rate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($device->ethernet_port->Visible) { // ethernet_port ?>
	<div id="r_ethernet_port" class="form-group">
		<label id="elh_device_ethernet_port" for="x_ethernet_port" class="col-sm-2 control-label ewLabel"><?php echo $device->ethernet_port->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $device->ethernet_port->CellAttributes() ?>>
<span id="el_device_ethernet_port">
<input type="text" data-table="device" data-field="x_ethernet_port" name="x_ethernet_port" id="x_ethernet_port" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($device->ethernet_port->getPlaceHolder()) ?>" value="<?php echo $device->ethernet_port->EditValue ?>"<?php echo $device->ethernet_port->EditAttributes() ?>>
</span>
<?php echo $device->ethernet_port->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($device->layar->Visible) { // layar ?>
	<div id="r_layar" class="form-group">
		<label id="elh_device_layar" for="x_layar" class="col-sm-2 control-label ewLabel"><?php echo $device->layar->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $device->layar->CellAttributes() ?>>
<span id="el_device_layar">
<input type="text" data-table="device" data-field="x_layar" name="x_layar" id="x_layar" size="30" placeholder="<?php echo ew_HtmlEncode($device->layar->getPlaceHolder()) ?>" value="<?php echo $device->layar->EditValue ?>"<?php echo $device->layar->EditAttributes() ?>>
</span>
<?php echo $device->layar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($device->alg_ver->Visible) { // alg_ver ?>
	<div id="r_alg_ver" class="form-group">
		<label id="elh_device_alg_ver" for="x_alg_ver" class="col-sm-2 control-label ewLabel"><?php echo $device->alg_ver->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $device->alg_ver->CellAttributes() ?>>
<span id="el_device_alg_ver">
<input type="text" data-table="device" data-field="x_alg_ver" name="x_alg_ver" id="x_alg_ver" size="30" placeholder="<?php echo ew_HtmlEncode($device->alg_ver->getPlaceHolder()) ?>" value="<?php echo $device->alg_ver->EditValue ?>"<?php echo $device->alg_ver->EditAttributes() ?>>
</span>
<?php echo $device->alg_ver->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($device->use_realtime->Visible) { // use_realtime ?>
	<div id="r_use_realtime" class="form-group">
		<label id="elh_device_use_realtime" for="x_use_realtime" class="col-sm-2 control-label ewLabel"><?php echo $device->use_realtime->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $device->use_realtime->CellAttributes() ?>>
<span id="el_device_use_realtime">
<input type="text" data-table="device" data-field="x_use_realtime" name="x_use_realtime" id="x_use_realtime" size="30" placeholder="<?php echo ew_HtmlEncode($device->use_realtime->getPlaceHolder()) ?>" value="<?php echo $device->use_realtime->EditValue ?>"<?php echo $device->use_realtime->EditAttributes() ?>>
</span>
<?php echo $device->use_realtime->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($device->group_realtime->Visible) { // group_realtime ?>
	<div id="r_group_realtime" class="form-group">
		<label id="elh_device_group_realtime" for="x_group_realtime" class="col-sm-2 control-label ewLabel"><?php echo $device->group_realtime->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $device->group_realtime->CellAttributes() ?>>
<span id="el_device_group_realtime">
<input type="text" data-table="device" data-field="x_group_realtime" name="x_group_realtime" id="x_group_realtime" size="30" placeholder="<?php echo ew_HtmlEncode($device->group_realtime->getPlaceHolder()) ?>" value="<?php echo $device->group_realtime->EditValue ?>"<?php echo $device->group_realtime->EditAttributes() ?>>
</span>
<?php echo $device->group_realtime->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($device->last_download->Visible) { // last_download ?>
	<div id="r_last_download" class="form-group">
		<label id="elh_device_last_download" for="x_last_download" class="col-sm-2 control-label ewLabel"><?php echo $device->last_download->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $device->last_download->CellAttributes() ?>>
<span id="el_device_last_download">
<input type="text" data-table="device" data-field="x_last_download" name="x_last_download" id="x_last_download" placeholder="<?php echo ew_HtmlEncode($device->last_download->getPlaceHolder()) ?>" value="<?php echo $device->last_download->EditValue ?>"<?php echo $device->last_download->EditAttributes() ?>>
</span>
<?php echo $device->last_download->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($device->ATTLOGStamp->Visible) { // ATTLOGStamp ?>
	<div id="r_ATTLOGStamp" class="form-group">
		<label id="elh_device_ATTLOGStamp" for="x_ATTLOGStamp" class="col-sm-2 control-label ewLabel"><?php echo $device->ATTLOGStamp->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $device->ATTLOGStamp->CellAttributes() ?>>
<span id="el_device_ATTLOGStamp">
<input type="text" data-table="device" data-field="x_ATTLOGStamp" name="x_ATTLOGStamp" id="x_ATTLOGStamp" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($device->ATTLOGStamp->getPlaceHolder()) ?>" value="<?php echo $device->ATTLOGStamp->EditValue ?>"<?php echo $device->ATTLOGStamp->EditAttributes() ?>>
</span>
<?php echo $device->ATTLOGStamp->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($device->OPERLOGStamp->Visible) { // OPERLOGStamp ?>
	<div id="r_OPERLOGStamp" class="form-group">
		<label id="elh_device_OPERLOGStamp" for="x_OPERLOGStamp" class="col-sm-2 control-label ewLabel"><?php echo $device->OPERLOGStamp->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $device->OPERLOGStamp->CellAttributes() ?>>
<span id="el_device_OPERLOGStamp">
<input type="text" data-table="device" data-field="x_OPERLOGStamp" name="x_OPERLOGStamp" id="x_OPERLOGStamp" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($device->OPERLOGStamp->getPlaceHolder()) ?>" value="<?php echo $device->OPERLOGStamp->EditValue ?>"<?php echo $device->OPERLOGStamp->EditAttributes() ?>>
</span>
<?php echo $device->OPERLOGStamp->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($device->ATTPHOTOStamp->Visible) { // ATTPHOTOStamp ?>
	<div id="r_ATTPHOTOStamp" class="form-group">
		<label id="elh_device_ATTPHOTOStamp" for="x_ATTPHOTOStamp" class="col-sm-2 control-label ewLabel"><?php echo $device->ATTPHOTOStamp->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $device->ATTPHOTOStamp->CellAttributes() ?>>
<span id="el_device_ATTPHOTOStamp">
<input type="text" data-table="device" data-field="x_ATTPHOTOStamp" name="x_ATTPHOTOStamp" id="x_ATTPHOTOStamp" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($device->ATTPHOTOStamp->getPlaceHolder()) ?>" value="<?php echo $device->ATTPHOTOStamp->EditValue ?>"<?php echo $device->ATTPHOTOStamp->EditAttributes() ?>>
</span>
<?php echo $device->ATTPHOTOStamp->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$device_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $device_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fdeviceadd.Init();
</script>
<?php
$device_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$device_add->Page_Terminate();
?>
