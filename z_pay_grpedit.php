<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "z_pay_grpinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$z_pay_grp_edit = NULL; // Initialize page object first

class cz_pay_grp_edit extends cz_pay_grp {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{0B4A4F9E-7A2B-4234-9791-3975C1DCDDA1}";

	// Table name
	var $TableName = 'z_pay_grp';

	// Page object name
	var $PageObjName = 'z_pay_grp_edit';

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

		// Table object (z_pay_grp)
		if (!isset($GLOBALS["z_pay_grp"]) || get_class($GLOBALS["z_pay_grp"]) == "cz_pay_grp") {
			$GLOBALS["z_pay_grp"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["z_pay_grp"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'z_pay_grp', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("z_pay_grplist.php"));
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
		$this->grp_id->SetVisibility();
		$this->kode_grp->SetVisibility();
		$this->grp_name->SetVisibility();
		$this->use_pengurang->SetVisibility();
		$this->type_pengurang->SetVisibility();
		$this->pengurang_persen->SetVisibility();
		$this->lastupdate_date->SetVisibility();
		$this->lastupdate_user->SetVisibility();

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
		global $EW_EXPORT, $z_pay_grp;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($z_pay_grp);
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
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;

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

		// Load key from QueryString
		if (@$_GET["grp_id"] <> "") {
			$this->grp_id->setQueryStringValue($_GET["grp_id"]);
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->grp_id->CurrentValue == "") {
			$this->Page_Terminate("z_pay_grplist.php"); // Invalid key, return to list
		}

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("z_pay_grplist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "z_pay_grplist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
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

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->grp_id->FldIsDetailKey) {
			$this->grp_id->setFormValue($objForm->GetValue("x_grp_id"));
		}
		if (!$this->kode_grp->FldIsDetailKey) {
			$this->kode_grp->setFormValue($objForm->GetValue("x_kode_grp"));
		}
		if (!$this->grp_name->FldIsDetailKey) {
			$this->grp_name->setFormValue($objForm->GetValue("x_grp_name"));
		}
		if (!$this->use_pengurang->FldIsDetailKey) {
			$this->use_pengurang->setFormValue($objForm->GetValue("x_use_pengurang"));
		}
		if (!$this->type_pengurang->FldIsDetailKey) {
			$this->type_pengurang->setFormValue($objForm->GetValue("x_type_pengurang"));
		}
		if (!$this->pengurang_persen->FldIsDetailKey) {
			$this->pengurang_persen->setFormValue($objForm->GetValue("x_pengurang_persen"));
		}
		if (!$this->lastupdate_date->FldIsDetailKey) {
			$this->lastupdate_date->setFormValue($objForm->GetValue("x_lastupdate_date"));
			$this->lastupdate_date->CurrentValue = ew_UnFormatDateTime($this->lastupdate_date->CurrentValue, 0);
		}
		if (!$this->lastupdate_user->FldIsDetailKey) {
			$this->lastupdate_user->setFormValue($objForm->GetValue("x_lastupdate_user"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->grp_id->CurrentValue = $this->grp_id->FormValue;
		$this->kode_grp->CurrentValue = $this->kode_grp->FormValue;
		$this->grp_name->CurrentValue = $this->grp_name->FormValue;
		$this->use_pengurang->CurrentValue = $this->use_pengurang->FormValue;
		$this->type_pengurang->CurrentValue = $this->type_pengurang->FormValue;
		$this->pengurang_persen->CurrentValue = $this->pengurang_persen->FormValue;
		$this->lastupdate_date->CurrentValue = $this->lastupdate_date->FormValue;
		$this->lastupdate_date->CurrentValue = ew_UnFormatDateTime($this->lastupdate_date->CurrentValue, 0);
		$this->lastupdate_user->CurrentValue = $this->lastupdate_user->FormValue;
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
		$this->grp_id->setDbValue($rs->fields('grp_id'));
		$this->kode_grp->setDbValue($rs->fields('kode_grp'));
		$this->grp_name->setDbValue($rs->fields('grp_name'));
		$this->use_pengurang->setDbValue($rs->fields('use_pengurang'));
		$this->type_pengurang->setDbValue($rs->fields('type_pengurang'));
		$this->pengurang_persen->setDbValue($rs->fields('pengurang_persen'));
		$this->lastupdate_date->setDbValue($rs->fields('lastupdate_date'));
		$this->lastupdate_user->setDbValue($rs->fields('lastupdate_user'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->grp_id->DbValue = $row['grp_id'];
		$this->kode_grp->DbValue = $row['kode_grp'];
		$this->grp_name->DbValue = $row['grp_name'];
		$this->use_pengurang->DbValue = $row['use_pengurang'];
		$this->type_pengurang->DbValue = $row['type_pengurang'];
		$this->pengurang_persen->DbValue = $row['pengurang_persen'];
		$this->lastupdate_date->DbValue = $row['lastupdate_date'];
		$this->lastupdate_user->DbValue = $row['lastupdate_user'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->pengurang_persen->FormValue == $this->pengurang_persen->CurrentValue && is_numeric(ew_StrToFloat($this->pengurang_persen->CurrentValue)))
			$this->pengurang_persen->CurrentValue = ew_StrToFloat($this->pengurang_persen->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// grp_id
		// kode_grp
		// grp_name
		// use_pengurang
		// type_pengurang
		// pengurang_persen
		// lastupdate_date
		// lastupdate_user

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// grp_id
		$this->grp_id->ViewValue = $this->grp_id->CurrentValue;
		$this->grp_id->ViewCustomAttributes = "";

		// kode_grp
		$this->kode_grp->ViewValue = $this->kode_grp->CurrentValue;
		$this->kode_grp->ViewCustomAttributes = "";

		// grp_name
		$this->grp_name->ViewValue = $this->grp_name->CurrentValue;
		$this->grp_name->ViewCustomAttributes = "";

		// use_pengurang
		$this->use_pengurang->ViewValue = $this->use_pengurang->CurrentValue;
		$this->use_pengurang->ViewCustomAttributes = "";

		// type_pengurang
		$this->type_pengurang->ViewValue = $this->type_pengurang->CurrentValue;
		$this->type_pengurang->ViewCustomAttributes = "";

		// pengurang_persen
		$this->pengurang_persen->ViewValue = $this->pengurang_persen->CurrentValue;
		$this->pengurang_persen->ViewCustomAttributes = "";

		// lastupdate_date
		$this->lastupdate_date->ViewValue = $this->lastupdate_date->CurrentValue;
		$this->lastupdate_date->ViewValue = ew_FormatDateTime($this->lastupdate_date->ViewValue, 0);
		$this->lastupdate_date->ViewCustomAttributes = "";

		// lastupdate_user
		$this->lastupdate_user->ViewValue = $this->lastupdate_user->CurrentValue;
		$this->lastupdate_user->ViewCustomAttributes = "";

			// grp_id
			$this->grp_id->LinkCustomAttributes = "";
			$this->grp_id->HrefValue = "";
			$this->grp_id->TooltipValue = "";

			// kode_grp
			$this->kode_grp->LinkCustomAttributes = "";
			$this->kode_grp->HrefValue = "";
			$this->kode_grp->TooltipValue = "";

			// grp_name
			$this->grp_name->LinkCustomAttributes = "";
			$this->grp_name->HrefValue = "";
			$this->grp_name->TooltipValue = "";

			// use_pengurang
			$this->use_pengurang->LinkCustomAttributes = "";
			$this->use_pengurang->HrefValue = "";
			$this->use_pengurang->TooltipValue = "";

			// type_pengurang
			$this->type_pengurang->LinkCustomAttributes = "";
			$this->type_pengurang->HrefValue = "";
			$this->type_pengurang->TooltipValue = "";

			// pengurang_persen
			$this->pengurang_persen->LinkCustomAttributes = "";
			$this->pengurang_persen->HrefValue = "";
			$this->pengurang_persen->TooltipValue = "";

			// lastupdate_date
			$this->lastupdate_date->LinkCustomAttributes = "";
			$this->lastupdate_date->HrefValue = "";
			$this->lastupdate_date->TooltipValue = "";

			// lastupdate_user
			$this->lastupdate_user->LinkCustomAttributes = "";
			$this->lastupdate_user->HrefValue = "";
			$this->lastupdate_user->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// grp_id
			$this->grp_id->EditAttrs["class"] = "form-control";
			$this->grp_id->EditCustomAttributes = "";
			$this->grp_id->EditValue = $this->grp_id->CurrentValue;
			$this->grp_id->ViewCustomAttributes = "";

			// kode_grp
			$this->kode_grp->EditAttrs["class"] = "form-control";
			$this->kode_grp->EditCustomAttributes = "";
			$this->kode_grp->EditValue = ew_HtmlEncode($this->kode_grp->CurrentValue);
			$this->kode_grp->PlaceHolder = ew_RemoveHtml($this->kode_grp->FldCaption());

			// grp_name
			$this->grp_name->EditAttrs["class"] = "form-control";
			$this->grp_name->EditCustomAttributes = "";
			$this->grp_name->EditValue = ew_HtmlEncode($this->grp_name->CurrentValue);
			$this->grp_name->PlaceHolder = ew_RemoveHtml($this->grp_name->FldCaption());

			// use_pengurang
			$this->use_pengurang->EditAttrs["class"] = "form-control";
			$this->use_pengurang->EditCustomAttributes = "";
			$this->use_pengurang->EditValue = ew_HtmlEncode($this->use_pengurang->CurrentValue);
			$this->use_pengurang->PlaceHolder = ew_RemoveHtml($this->use_pengurang->FldCaption());

			// type_pengurang
			$this->type_pengurang->EditAttrs["class"] = "form-control";
			$this->type_pengurang->EditCustomAttributes = "";
			$this->type_pengurang->EditValue = ew_HtmlEncode($this->type_pengurang->CurrentValue);
			$this->type_pengurang->PlaceHolder = ew_RemoveHtml($this->type_pengurang->FldCaption());

			// pengurang_persen
			$this->pengurang_persen->EditAttrs["class"] = "form-control";
			$this->pengurang_persen->EditCustomAttributes = "";
			$this->pengurang_persen->EditValue = ew_HtmlEncode($this->pengurang_persen->CurrentValue);
			$this->pengurang_persen->PlaceHolder = ew_RemoveHtml($this->pengurang_persen->FldCaption());
			if (strval($this->pengurang_persen->EditValue) <> "" && is_numeric($this->pengurang_persen->EditValue)) $this->pengurang_persen->EditValue = ew_FormatNumber($this->pengurang_persen->EditValue, -2, -1, -2, 0);

			// lastupdate_date
			$this->lastupdate_date->EditAttrs["class"] = "form-control";
			$this->lastupdate_date->EditCustomAttributes = "";
			$this->lastupdate_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->lastupdate_date->CurrentValue, 8));
			$this->lastupdate_date->PlaceHolder = ew_RemoveHtml($this->lastupdate_date->FldCaption());

			// lastupdate_user
			$this->lastupdate_user->EditAttrs["class"] = "form-control";
			$this->lastupdate_user->EditCustomAttributes = "";
			$this->lastupdate_user->EditValue = ew_HtmlEncode($this->lastupdate_user->CurrentValue);
			$this->lastupdate_user->PlaceHolder = ew_RemoveHtml($this->lastupdate_user->FldCaption());

			// Edit refer script
			// grp_id

			$this->grp_id->LinkCustomAttributes = "";
			$this->grp_id->HrefValue = "";

			// kode_grp
			$this->kode_grp->LinkCustomAttributes = "";
			$this->kode_grp->HrefValue = "";

			// grp_name
			$this->grp_name->LinkCustomAttributes = "";
			$this->grp_name->HrefValue = "";

			// use_pengurang
			$this->use_pengurang->LinkCustomAttributes = "";
			$this->use_pengurang->HrefValue = "";

			// type_pengurang
			$this->type_pengurang->LinkCustomAttributes = "";
			$this->type_pengurang->HrefValue = "";

			// pengurang_persen
			$this->pengurang_persen->LinkCustomAttributes = "";
			$this->pengurang_persen->HrefValue = "";

			// lastupdate_date
			$this->lastupdate_date->LinkCustomAttributes = "";
			$this->lastupdate_date->HrefValue = "";

			// lastupdate_user
			$this->lastupdate_user->LinkCustomAttributes = "";
			$this->lastupdate_user->HrefValue = "";
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
		if (!$this->grp_id->FldIsDetailKey && !is_null($this->grp_id->FormValue) && $this->grp_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->grp_id->FldCaption(), $this->grp_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->grp_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->grp_id->FldErrMsg());
		}
		if (!$this->kode_grp->FldIsDetailKey && !is_null($this->kode_grp->FormValue) && $this->kode_grp->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->kode_grp->FldCaption(), $this->kode_grp->ReqErrMsg));
		}
		if (!$this->grp_name->FldIsDetailKey && !is_null($this->grp_name->FormValue) && $this->grp_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->grp_name->FldCaption(), $this->grp_name->ReqErrMsg));
		}
		if (!$this->use_pengurang->FldIsDetailKey && !is_null($this->use_pengurang->FormValue) && $this->use_pengurang->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->use_pengurang->FldCaption(), $this->use_pengurang->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->use_pengurang->FormValue)) {
			ew_AddMessage($gsFormError, $this->use_pengurang->FldErrMsg());
		}
		if (!$this->type_pengurang->FldIsDetailKey && !is_null($this->type_pengurang->FormValue) && $this->type_pengurang->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->type_pengurang->FldCaption(), $this->type_pengurang->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->type_pengurang->FormValue)) {
			ew_AddMessage($gsFormError, $this->type_pengurang->FldErrMsg());
		}
		if (!$this->pengurang_persen->FldIsDetailKey && !is_null($this->pengurang_persen->FormValue) && $this->pengurang_persen->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pengurang_persen->FldCaption(), $this->pengurang_persen->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->pengurang_persen->FormValue)) {
			ew_AddMessage($gsFormError, $this->pengurang_persen->FldErrMsg());
		}
		if (!$this->lastupdate_date->FldIsDetailKey && !is_null($this->lastupdate_date->FormValue) && $this->lastupdate_date->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->lastupdate_date->FldCaption(), $this->lastupdate_date->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->lastupdate_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->lastupdate_date->FldErrMsg());
		}
		if (!$this->lastupdate_user->FldIsDetailKey && !is_null($this->lastupdate_user->FormValue) && $this->lastupdate_user->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->lastupdate_user->FldCaption(), $this->lastupdate_user->ReqErrMsg));
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

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// grp_id
			// kode_grp

			$this->kode_grp->SetDbValueDef($rsnew, $this->kode_grp->CurrentValue, "", $this->kode_grp->ReadOnly);

			// grp_name
			$this->grp_name->SetDbValueDef($rsnew, $this->grp_name->CurrentValue, "", $this->grp_name->ReadOnly);

			// use_pengurang
			$this->use_pengurang->SetDbValueDef($rsnew, $this->use_pengurang->CurrentValue, 0, $this->use_pengurang->ReadOnly);

			// type_pengurang
			$this->type_pengurang->SetDbValueDef($rsnew, $this->type_pengurang->CurrentValue, 0, $this->type_pengurang->ReadOnly);

			// pengurang_persen
			$this->pengurang_persen->SetDbValueDef($rsnew, $this->pengurang_persen->CurrentValue, 0, $this->pengurang_persen->ReadOnly);

			// lastupdate_date
			$this->lastupdate_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->lastupdate_date->CurrentValue, 0), ew_CurrentDate(), $this->lastupdate_date->ReadOnly);

			// lastupdate_user
			$this->lastupdate_user->SetDbValueDef($rsnew, $this->lastupdate_user->CurrentValue, "", $this->lastupdate_user->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("z_pay_grplist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
if (!isset($z_pay_grp_edit)) $z_pay_grp_edit = new cz_pay_grp_edit();

// Page init
$z_pay_grp_edit->Page_Init();

// Page main
$z_pay_grp_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$z_pay_grp_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fz_pay_grpedit = new ew_Form("fz_pay_grpedit", "edit");

// Validate form
fz_pay_grpedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_grp_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_grp->grp_id->FldCaption(), $z_pay_grp->grp_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_grp_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_grp->grp_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_kode_grp");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_grp->kode_grp->FldCaption(), $z_pay_grp->kode_grp->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_grp_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_grp->grp_name->FldCaption(), $z_pay_grp->grp_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_use_pengurang");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_grp->use_pengurang->FldCaption(), $z_pay_grp->use_pengurang->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_use_pengurang");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_grp->use_pengurang->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_type_pengurang");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_grp->type_pengurang->FldCaption(), $z_pay_grp->type_pengurang->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_type_pengurang");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_grp->type_pengurang->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pengurang_persen");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_grp->pengurang_persen->FldCaption(), $z_pay_grp->pengurang_persen->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pengurang_persen");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_grp->pengurang_persen->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_lastupdate_date");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_grp->lastupdate_date->FldCaption(), $z_pay_grp->lastupdate_date->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_lastupdate_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_grp->lastupdate_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_lastupdate_user");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_grp->lastupdate_user->FldCaption(), $z_pay_grp->lastupdate_user->ReqErrMsg)) ?>");

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
fz_pay_grpedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fz_pay_grpedit.ValidateRequired = true;
<?php } else { ?>
fz_pay_grpedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$z_pay_grp_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $z_pay_grp_edit->ShowPageHeader(); ?>
<?php
$z_pay_grp_edit->ShowMessage();
?>
<form name="fz_pay_grpedit" id="fz_pay_grpedit" class="<?php echo $z_pay_grp_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($z_pay_grp_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $z_pay_grp_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="z_pay_grp">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($z_pay_grp_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($z_pay_grp->grp_id->Visible) { // grp_id ?>
	<div id="r_grp_id" class="form-group">
		<label id="elh_z_pay_grp_grp_id" for="x_grp_id" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_grp->grp_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_grp->grp_id->CellAttributes() ?>>
<span id="el_z_pay_grp_grp_id">
<span<?php echo $z_pay_grp->grp_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $z_pay_grp->grp_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="z_pay_grp" data-field="x_grp_id" name="x_grp_id" id="x_grp_id" value="<?php echo ew_HtmlEncode($z_pay_grp->grp_id->CurrentValue) ?>">
<?php echo $z_pay_grp->grp_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_grp->kode_grp->Visible) { // kode_grp ?>
	<div id="r_kode_grp" class="form-group">
		<label id="elh_z_pay_grp_kode_grp" for="x_kode_grp" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_grp->kode_grp->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_grp->kode_grp->CellAttributes() ?>>
<span id="el_z_pay_grp_kode_grp">
<input type="text" data-table="z_pay_grp" data-field="x_kode_grp" name="x_kode_grp" id="x_kode_grp" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($z_pay_grp->kode_grp->getPlaceHolder()) ?>" value="<?php echo $z_pay_grp->kode_grp->EditValue ?>"<?php echo $z_pay_grp->kode_grp->EditAttributes() ?>>
</span>
<?php echo $z_pay_grp->kode_grp->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_grp->grp_name->Visible) { // grp_name ?>
	<div id="r_grp_name" class="form-group">
		<label id="elh_z_pay_grp_grp_name" for="x_grp_name" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_grp->grp_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_grp->grp_name->CellAttributes() ?>>
<span id="el_z_pay_grp_grp_name">
<input type="text" data-table="z_pay_grp" data-field="x_grp_name" name="x_grp_name" id="x_grp_name" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($z_pay_grp->grp_name->getPlaceHolder()) ?>" value="<?php echo $z_pay_grp->grp_name->EditValue ?>"<?php echo $z_pay_grp->grp_name->EditAttributes() ?>>
</span>
<?php echo $z_pay_grp->grp_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_grp->use_pengurang->Visible) { // use_pengurang ?>
	<div id="r_use_pengurang" class="form-group">
		<label id="elh_z_pay_grp_use_pengurang" for="x_use_pengurang" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_grp->use_pengurang->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_grp->use_pengurang->CellAttributes() ?>>
<span id="el_z_pay_grp_use_pengurang">
<input type="text" data-table="z_pay_grp" data-field="x_use_pengurang" name="x_use_pengurang" id="x_use_pengurang" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_grp->use_pengurang->getPlaceHolder()) ?>" value="<?php echo $z_pay_grp->use_pengurang->EditValue ?>"<?php echo $z_pay_grp->use_pengurang->EditAttributes() ?>>
</span>
<?php echo $z_pay_grp->use_pengurang->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_grp->type_pengurang->Visible) { // type_pengurang ?>
	<div id="r_type_pengurang" class="form-group">
		<label id="elh_z_pay_grp_type_pengurang" for="x_type_pengurang" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_grp->type_pengurang->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_grp->type_pengurang->CellAttributes() ?>>
<span id="el_z_pay_grp_type_pengurang">
<input type="text" data-table="z_pay_grp" data-field="x_type_pengurang" name="x_type_pengurang" id="x_type_pengurang" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_grp->type_pengurang->getPlaceHolder()) ?>" value="<?php echo $z_pay_grp->type_pengurang->EditValue ?>"<?php echo $z_pay_grp->type_pengurang->EditAttributes() ?>>
</span>
<?php echo $z_pay_grp->type_pengurang->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_grp->pengurang_persen->Visible) { // pengurang_persen ?>
	<div id="r_pengurang_persen" class="form-group">
		<label id="elh_z_pay_grp_pengurang_persen" for="x_pengurang_persen" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_grp->pengurang_persen->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_grp->pengurang_persen->CellAttributes() ?>>
<span id="el_z_pay_grp_pengurang_persen">
<input type="text" data-table="z_pay_grp" data-field="x_pengurang_persen" name="x_pengurang_persen" id="x_pengurang_persen" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_grp->pengurang_persen->getPlaceHolder()) ?>" value="<?php echo $z_pay_grp->pengurang_persen->EditValue ?>"<?php echo $z_pay_grp->pengurang_persen->EditAttributes() ?>>
</span>
<?php echo $z_pay_grp->pengurang_persen->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_grp->lastupdate_date->Visible) { // lastupdate_date ?>
	<div id="r_lastupdate_date" class="form-group">
		<label id="elh_z_pay_grp_lastupdate_date" for="x_lastupdate_date" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_grp->lastupdate_date->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_grp->lastupdate_date->CellAttributes() ?>>
<span id="el_z_pay_grp_lastupdate_date">
<input type="text" data-table="z_pay_grp" data-field="x_lastupdate_date" name="x_lastupdate_date" id="x_lastupdate_date" placeholder="<?php echo ew_HtmlEncode($z_pay_grp->lastupdate_date->getPlaceHolder()) ?>" value="<?php echo $z_pay_grp->lastupdate_date->EditValue ?>"<?php echo $z_pay_grp->lastupdate_date->EditAttributes() ?>>
</span>
<?php echo $z_pay_grp->lastupdate_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_grp->lastupdate_user->Visible) { // lastupdate_user ?>
	<div id="r_lastupdate_user" class="form-group">
		<label id="elh_z_pay_grp_lastupdate_user" for="x_lastupdate_user" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_grp->lastupdate_user->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_grp->lastupdate_user->CellAttributes() ?>>
<span id="el_z_pay_grp_lastupdate_user">
<input type="text" data-table="z_pay_grp" data-field="x_lastupdate_user" name="x_lastupdate_user" id="x_lastupdate_user" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($z_pay_grp->lastupdate_user->getPlaceHolder()) ?>" value="<?php echo $z_pay_grp->lastupdate_user->EditValue ?>"<?php echo $z_pay_grp->lastupdate_user->EditAttributes() ?>>
</span>
<?php echo $z_pay_grp->lastupdate_user->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$z_pay_grp_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $z_pay_grp_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fz_pay_grpedit.Init();
</script>
<?php
$z_pay_grp_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$z_pay_grp_edit->Page_Terminate();
?>
