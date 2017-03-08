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

$device_view = NULL; // Initialize page object first

class cdevice_view extends cdevice {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{0B4A4F9E-7A2B-4234-9791-3975C1DCDDA1}";

	// Table name
	var $TableName = 'device';

	// Page object name
	var $PageObjName = 'device_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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
		$KeyUrl = "";
		if (@$_GET["sn"] <> "") {
			$this->RecKey["sn"] = $_GET["sn"];
			$KeyUrl .= "&amp;sn=" . urlencode($this->RecKey["sn"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

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

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		if (!$Security->CanView()) {
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

		// Get export parameters
		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header
		if (@$_GET["sn"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["sn"]);
		}

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Setup export options
		$this->SetupExportOptions();
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $IsModal = FALSE;
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["sn"] <> "") {
				$this->sn->setQueryStringValue($_GET["sn"]);
				$this->RecKey["sn"] = $this->sn->QueryStringValue;
			} elseif (@$_POST["sn"] <> "") {
				$this->sn->setFormValue($_POST["sn"]);
				$this->RecKey["sn"] = $this->sn->FormValue;
			} else {
				$sReturnUrl = "devicelist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "devicelist.php"; // No matching record, return to list
					}
			}

			// Export data only
			if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "devicelist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("ViewPageAddLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->AddUrl) . "',caption:'" . $addcaption . "'});\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$editcaption = ew_HtmlTitle($Language->Phrase("ViewPageEditLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->EditUrl) . "',caption:'" . $editcaption . "'});\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Copy
		$item = &$option->Add("copy");
		$copycaption = ew_HtmlTitle($Language->Phrase("ViewPageCopyLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->CopyUrl) . "',caption:'" . $copycaption . "'});\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->CanAdd());

		// Delete
		$item = &$option->Add("delete");
		if ($this->IsModal) // Handle as inline delete
			$item->Body = "<a onclick=\"return ew_ConfirmDelete(this);\" class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode(ew_AddQueryStringToUrl($this->DeleteUrl, "a_delete=1")) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = TRUE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = TRUE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = TRUE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = TRUE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_device\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_device',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fdeviceview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = TRUE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = TRUE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = FALSE;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;
		$this->SetUpStartRec(); // Set up start record position

		// Set the last record to display
		if ($this->DisplayRecs <= 0) {
			$this->StopRec = $this->TotalRecs;
		} else {
			$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
		}
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "v");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "view");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Call Page Exported server event
		$this->Page_Exported();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED && $this->Export <> "pdf")
			echo ew_DebugMsg();

		// Output data
		if ($this->Export == "email") {
			echo $this->ExportEmail($Doc->Text);
		} else {
			$Doc->Export();
		}
	}

	// Export email
	function ExportEmail($EmailContent) {
		global $gTmpImages, $Language;
		$sSender = @$_POST["sender"];
		$sRecipient = @$_POST["recipient"];
		$sCc = @$_POST["cc"];
		$sBcc = @$_POST["bcc"];
		$sContentType = @$_POST["contenttype"];

		// Subject
		$sSubject = ew_StripSlashes(@$_POST["subject"]);
		$sEmailSubject = $sSubject;

		// Message
		$sContent = ew_StripSlashes(@$_POST["message"]);
		$sEmailMessage = $sContent;

		// Check sender
		if ($sSender == "") {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterSenderEmail") . "</p>";
		}
		if (!ew_CheckEmail($sSender)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperSenderEmail") . "</p>";
		}

		// Check recipient
		if (!ew_CheckEmailList($sRecipient, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperRecipientEmail") . "</p>";
		}

		// Check cc
		if (!ew_CheckEmailList($sCc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperCcEmail") . "</p>";
		}

		// Check bcc
		if (!ew_CheckEmailList($sBcc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperBccEmail") . "</p>";
		}

		// Check email sent count
		if (!isset($_SESSION[EW_EXPORT_EMAIL_COUNTER]))
			$_SESSION[EW_EXPORT_EMAIL_COUNTER] = 0;
		if (intval($_SESSION[EW_EXPORT_EMAIL_COUNTER]) > EW_MAX_EMAIL_SENT_COUNT) {
			return "<p class=\"text-danger\">" . $Language->Phrase("ExceedMaxEmailExport") . "</p>";
		}

		// Send email
		$Email = new cEmail();
		$Email->Sender = $sSender; // Sender
		$Email->Recipient = $sRecipient; // Recipient
		$Email->Cc = $sCc; // Cc
		$Email->Bcc = $sBcc; // Bcc
		$Email->Subject = $sEmailSubject; // Subject
		$Email->Format = ($sContentType == "url") ? "text" : "html";
		if ($sEmailMessage <> "") {
			$sEmailMessage = ew_RemoveXSS($sEmailMessage);
			$sEmailMessage .= ($sContentType == "url") ? "\r\n\r\n" : "<br><br>";
		}
		if ($sContentType == "url") {
			$sUrl = ew_ConvertFullUrl(ew_CurrentPage() . "?" . $this->ExportQueryString());
			$sEmailMessage .= $sUrl; // Send URL only
		} else {
			foreach ($gTmpImages as $tmpimage)
				$Email->AddEmbeddedImage($tmpimage);
			$sEmailMessage .= ew_CleanEmailContent($EmailContent); // Send HTML
		}
		$Email->Content = $sEmailMessage; // Content
		$EventArgs = array();
		if ($this->Recordset) {
			$this->RecCnt = $this->StartRec - 1;
			$this->Recordset->MoveFirst();
			if ($this->StartRec > 1)
				$this->Recordset->Move($this->StartRec - 1);
			$EventArgs["rs"] = &$this->Recordset;
		}
		$bEmailSent = FALSE;
		if ($this->Email_Sending($Email, $EventArgs))
			$bEmailSent = $Email->Send();

		// Check email sent status
		if ($bEmailSent) {

			// Update email sent count
			$_SESSION[EW_EXPORT_EMAIL_COUNTER]++;

			// Sent email success
			return "<p class=\"text-success\">" . $Language->Phrase("SendEmailSuccess") . "</p>"; // Set up success message
		} else {

			// Sent email failure
			return "<p class=\"text-danger\">" . $Email->SendErrDescription . "</p>";
		}
	}

	// Export QueryString
	function ExportQueryString() {

		// Initialize
		$sQry = "export=html";

		// Add record key QueryString
		$sQry .= "&" . substr($this->KeyUrl("", ""), 1);
		return $sQry;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("devicelist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($device_view)) $device_view = new cdevice_view();

// Page init
$device_view->Page_Init();

// Page main
$device_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$device_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($device->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fdeviceview = new ew_Form("fdeviceview", "view");

// Form_CustomValidate event
fdeviceview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdeviceview.ValidateRequired = true;
<?php } else { ?>
fdeviceview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($device->Export == "") { ?>
<div class="ewToolbar">
<?php if (!$device_view->IsModal) { ?>
<?php if ($device->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php } ?>
<?php $device_view->ExportOptions->Render("body") ?>
<?php
	foreach ($device_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if (!$device_view->IsModal) { ?>
<?php if ($device->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $device_view->ShowPageHeader(); ?>
<?php
$device_view->ShowMessage();
?>
<form name="fdeviceview" id="fdeviceview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($device_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $device_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="device">
<?php if ($device_view->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($device->sn->Visible) { // sn ?>
	<tr id="r_sn">
		<td><span id="elh_device_sn"><?php echo $device->sn->FldCaption() ?></span></td>
		<td data-name="sn"<?php echo $device->sn->CellAttributes() ?>>
<span id="el_device_sn">
<span<?php echo $device->sn->ViewAttributes() ?>>
<?php echo $device->sn->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($device->activation_code->Visible) { // activation_code ?>
	<tr id="r_activation_code">
		<td><span id="elh_device_activation_code"><?php echo $device->activation_code->FldCaption() ?></span></td>
		<td data-name="activation_code"<?php echo $device->activation_code->CellAttributes() ?>>
<span id="el_device_activation_code">
<span<?php echo $device->activation_code->ViewAttributes() ?>>
<?php echo $device->activation_code->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($device->act_code_realtime->Visible) { // act_code_realtime ?>
	<tr id="r_act_code_realtime">
		<td><span id="elh_device_act_code_realtime"><?php echo $device->act_code_realtime->FldCaption() ?></span></td>
		<td data-name="act_code_realtime"<?php echo $device->act_code_realtime->CellAttributes() ?>>
<span id="el_device_act_code_realtime">
<span<?php echo $device->act_code_realtime->ViewAttributes() ?>>
<?php echo $device->act_code_realtime->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($device->device_name->Visible) { // device_name ?>
	<tr id="r_device_name">
		<td><span id="elh_device_device_name"><?php echo $device->device_name->FldCaption() ?></span></td>
		<td data-name="device_name"<?php echo $device->device_name->CellAttributes() ?>>
<span id="el_device_device_name">
<span<?php echo $device->device_name->ViewAttributes() ?>>
<?php echo $device->device_name->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($device->comm_key->Visible) { // comm_key ?>
	<tr id="r_comm_key">
		<td><span id="elh_device_comm_key"><?php echo $device->comm_key->FldCaption() ?></span></td>
		<td data-name="comm_key"<?php echo $device->comm_key->CellAttributes() ?>>
<span id="el_device_comm_key">
<span<?php echo $device->comm_key->ViewAttributes() ?>>
<?php echo $device->comm_key->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($device->dev_id->Visible) { // dev_id ?>
	<tr id="r_dev_id">
		<td><span id="elh_device_dev_id"><?php echo $device->dev_id->FldCaption() ?></span></td>
		<td data-name="dev_id"<?php echo $device->dev_id->CellAttributes() ?>>
<span id="el_device_dev_id">
<span<?php echo $device->dev_id->ViewAttributes() ?>>
<?php echo $device->dev_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($device->comm_type->Visible) { // comm_type ?>
	<tr id="r_comm_type">
		<td><span id="elh_device_comm_type"><?php echo $device->comm_type->FldCaption() ?></span></td>
		<td data-name="comm_type"<?php echo $device->comm_type->CellAttributes() ?>>
<span id="el_device_comm_type">
<span<?php echo $device->comm_type->ViewAttributes() ?>>
<?php echo $device->comm_type->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($device->ip_address->Visible) { // ip_address ?>
	<tr id="r_ip_address">
		<td><span id="elh_device_ip_address"><?php echo $device->ip_address->FldCaption() ?></span></td>
		<td data-name="ip_address"<?php echo $device->ip_address->CellAttributes() ?>>
<span id="el_device_ip_address">
<span<?php echo $device->ip_address->ViewAttributes() ?>>
<?php echo $device->ip_address->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($device->id_type->Visible) { // id_type ?>
	<tr id="r_id_type">
		<td><span id="elh_device_id_type"><?php echo $device->id_type->FldCaption() ?></span></td>
		<td data-name="id_type"<?php echo $device->id_type->CellAttributes() ?>>
<span id="el_device_id_type">
<span<?php echo $device->id_type->ViewAttributes() ?>>
<?php echo $device->id_type->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($device->dev_type->Visible) { // dev_type ?>
	<tr id="r_dev_type">
		<td><span id="elh_device_dev_type"><?php echo $device->dev_type->FldCaption() ?></span></td>
		<td data-name="dev_type"<?php echo $device->dev_type->CellAttributes() ?>>
<span id="el_device_dev_type">
<span<?php echo $device->dev_type->ViewAttributes() ?>>
<?php echo $device->dev_type->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($device->serial_port->Visible) { // serial_port ?>
	<tr id="r_serial_port">
		<td><span id="elh_device_serial_port"><?php echo $device->serial_port->FldCaption() ?></span></td>
		<td data-name="serial_port"<?php echo $device->serial_port->CellAttributes() ?>>
<span id="el_device_serial_port">
<span<?php echo $device->serial_port->ViewAttributes() ?>>
<?php echo $device->serial_port->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($device->baud_rate->Visible) { // baud_rate ?>
	<tr id="r_baud_rate">
		<td><span id="elh_device_baud_rate"><?php echo $device->baud_rate->FldCaption() ?></span></td>
		<td data-name="baud_rate"<?php echo $device->baud_rate->CellAttributes() ?>>
<span id="el_device_baud_rate">
<span<?php echo $device->baud_rate->ViewAttributes() ?>>
<?php echo $device->baud_rate->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($device->ethernet_port->Visible) { // ethernet_port ?>
	<tr id="r_ethernet_port">
		<td><span id="elh_device_ethernet_port"><?php echo $device->ethernet_port->FldCaption() ?></span></td>
		<td data-name="ethernet_port"<?php echo $device->ethernet_port->CellAttributes() ?>>
<span id="el_device_ethernet_port">
<span<?php echo $device->ethernet_port->ViewAttributes() ?>>
<?php echo $device->ethernet_port->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($device->layar->Visible) { // layar ?>
	<tr id="r_layar">
		<td><span id="elh_device_layar"><?php echo $device->layar->FldCaption() ?></span></td>
		<td data-name="layar"<?php echo $device->layar->CellAttributes() ?>>
<span id="el_device_layar">
<span<?php echo $device->layar->ViewAttributes() ?>>
<?php echo $device->layar->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($device->alg_ver->Visible) { // alg_ver ?>
	<tr id="r_alg_ver">
		<td><span id="elh_device_alg_ver"><?php echo $device->alg_ver->FldCaption() ?></span></td>
		<td data-name="alg_ver"<?php echo $device->alg_ver->CellAttributes() ?>>
<span id="el_device_alg_ver">
<span<?php echo $device->alg_ver->ViewAttributes() ?>>
<?php echo $device->alg_ver->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($device->use_realtime->Visible) { // use_realtime ?>
	<tr id="r_use_realtime">
		<td><span id="elh_device_use_realtime"><?php echo $device->use_realtime->FldCaption() ?></span></td>
		<td data-name="use_realtime"<?php echo $device->use_realtime->CellAttributes() ?>>
<span id="el_device_use_realtime">
<span<?php echo $device->use_realtime->ViewAttributes() ?>>
<?php echo $device->use_realtime->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($device->group_realtime->Visible) { // group_realtime ?>
	<tr id="r_group_realtime">
		<td><span id="elh_device_group_realtime"><?php echo $device->group_realtime->FldCaption() ?></span></td>
		<td data-name="group_realtime"<?php echo $device->group_realtime->CellAttributes() ?>>
<span id="el_device_group_realtime">
<span<?php echo $device->group_realtime->ViewAttributes() ?>>
<?php echo $device->group_realtime->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($device->last_download->Visible) { // last_download ?>
	<tr id="r_last_download">
		<td><span id="elh_device_last_download"><?php echo $device->last_download->FldCaption() ?></span></td>
		<td data-name="last_download"<?php echo $device->last_download->CellAttributes() ?>>
<span id="el_device_last_download">
<span<?php echo $device->last_download->ViewAttributes() ?>>
<?php echo $device->last_download->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($device->ATTLOGStamp->Visible) { // ATTLOGStamp ?>
	<tr id="r_ATTLOGStamp">
		<td><span id="elh_device_ATTLOGStamp"><?php echo $device->ATTLOGStamp->FldCaption() ?></span></td>
		<td data-name="ATTLOGStamp"<?php echo $device->ATTLOGStamp->CellAttributes() ?>>
<span id="el_device_ATTLOGStamp">
<span<?php echo $device->ATTLOGStamp->ViewAttributes() ?>>
<?php echo $device->ATTLOGStamp->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($device->OPERLOGStamp->Visible) { // OPERLOGStamp ?>
	<tr id="r_OPERLOGStamp">
		<td><span id="elh_device_OPERLOGStamp"><?php echo $device->OPERLOGStamp->FldCaption() ?></span></td>
		<td data-name="OPERLOGStamp"<?php echo $device->OPERLOGStamp->CellAttributes() ?>>
<span id="el_device_OPERLOGStamp">
<span<?php echo $device->OPERLOGStamp->ViewAttributes() ?>>
<?php echo $device->OPERLOGStamp->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($device->ATTPHOTOStamp->Visible) { // ATTPHOTOStamp ?>
	<tr id="r_ATTPHOTOStamp">
		<td><span id="elh_device_ATTPHOTOStamp"><?php echo $device->ATTPHOTOStamp->FldCaption() ?></span></td>
		<td data-name="ATTPHOTOStamp"<?php echo $device->ATTPHOTOStamp->CellAttributes() ?>>
<span id="el_device_ATTPHOTOStamp">
<span<?php echo $device->ATTPHOTOStamp->ViewAttributes() ?>>
<?php echo $device->ATTPHOTOStamp->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<?php if ($device->Export == "") { ?>
<script type="text/javascript">
fdeviceview.Init();
</script>
<?php } ?>
<?php
$device_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($device->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$device_view->Page_Terminate();
?>
