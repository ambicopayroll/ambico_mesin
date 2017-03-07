<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "grp_user_dinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$grp_user_d_add = NULL; // Initialize page object first

class cgrp_user_d_add extends cgrp_user_d {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F36F5C9B-7A33-450D-8126-2253575B79B0}";

	// Table name
	var $TableName = 'grp_user_d';

	// Page object name
	var $PageObjName = 'grp_user_d_add';

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

		// Table object (grp_user_d)
		if (!isset($GLOBALS["grp_user_d"]) || get_class($GLOBALS["grp_user_d"]) == "cgrp_user_d") {
			$GLOBALS["grp_user_d"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["grp_user_d"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'grp_user_d', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("grp_user_dlist.php"));
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
		$this->grp_user_id->SetVisibility();
		$this->tree_id->SetVisibility();
		$this->level_tree->SetVisibility();
		$this->com_id->SetVisibility();
		$this->com_form->SetVisibility();
		$this->com_name->SetVisibility();
		$this->caption->SetVisibility();
		$this->urutan->SetVisibility();
		$this->app_name->SetVisibility();

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
		global $EW_EXPORT, $grp_user_d;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($grp_user_d);
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
			if (@$_GET["com_id"] != "") {
				$this->com_id->setQueryStringValue($_GET["com_id"]);
				$this->setKey("com_id", $this->com_id->CurrentValue); // Set up key
			} else {
				$this->setKey("com_id", ""); // Clear key
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
					$this->Page_Terminate("grp_user_dlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "grp_user_dlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "grp_user_dview.php")
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
		$this->grp_user_id->CurrentValue = NULL;
		$this->grp_user_id->OldValue = $this->grp_user_id->CurrentValue;
		$this->tree_id->CurrentValue = NULL;
		$this->tree_id->OldValue = $this->tree_id->CurrentValue;
		$this->level_tree->CurrentValue = 0;
		$this->com_id->CurrentValue = NULL;
		$this->com_id->OldValue = $this->com_id->CurrentValue;
		$this->com_form->CurrentValue = NULL;
		$this->com_form->OldValue = $this->com_form->CurrentValue;
		$this->com_name->CurrentValue = NULL;
		$this->com_name->OldValue = $this->com_name->CurrentValue;
		$this->caption->CurrentValue = NULL;
		$this->caption->OldValue = $this->caption->CurrentValue;
		$this->urutan->CurrentValue = NULL;
		$this->urutan->OldValue = $this->urutan->CurrentValue;
		$this->app_name->CurrentValue = NULL;
		$this->app_name->OldValue = $this->app_name->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->grp_user_id->FldIsDetailKey) {
			$this->grp_user_id->setFormValue($objForm->GetValue("x_grp_user_id"));
		}
		if (!$this->tree_id->FldIsDetailKey) {
			$this->tree_id->setFormValue($objForm->GetValue("x_tree_id"));
		}
		if (!$this->level_tree->FldIsDetailKey) {
			$this->level_tree->setFormValue($objForm->GetValue("x_level_tree"));
		}
		if (!$this->com_id->FldIsDetailKey) {
			$this->com_id->setFormValue($objForm->GetValue("x_com_id"));
		}
		if (!$this->com_form->FldIsDetailKey) {
			$this->com_form->setFormValue($objForm->GetValue("x_com_form"));
		}
		if (!$this->com_name->FldIsDetailKey) {
			$this->com_name->setFormValue($objForm->GetValue("x_com_name"));
		}
		if (!$this->caption->FldIsDetailKey) {
			$this->caption->setFormValue($objForm->GetValue("x_caption"));
		}
		if (!$this->urutan->FldIsDetailKey) {
			$this->urutan->setFormValue($objForm->GetValue("x_urutan"));
		}
		if (!$this->app_name->FldIsDetailKey) {
			$this->app_name->setFormValue($objForm->GetValue("x_app_name"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->grp_user_id->CurrentValue = $this->grp_user_id->FormValue;
		$this->tree_id->CurrentValue = $this->tree_id->FormValue;
		$this->level_tree->CurrentValue = $this->level_tree->FormValue;
		$this->com_id->CurrentValue = $this->com_id->FormValue;
		$this->com_form->CurrentValue = $this->com_form->FormValue;
		$this->com_name->CurrentValue = $this->com_name->FormValue;
		$this->caption->CurrentValue = $this->caption->FormValue;
		$this->urutan->CurrentValue = $this->urutan->FormValue;
		$this->app_name->CurrentValue = $this->app_name->FormValue;
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
		$this->grp_user_id->setDbValue($rs->fields('grp_user_id'));
		$this->tree_id->setDbValue($rs->fields('tree_id'));
		$this->level_tree->setDbValue($rs->fields('level_tree'));
		$this->com_id->setDbValue($rs->fields('com_id'));
		$this->com_form->setDbValue($rs->fields('com_form'));
		$this->com_name->setDbValue($rs->fields('com_name'));
		$this->caption->setDbValue($rs->fields('caption'));
		$this->urutan->setDbValue($rs->fields('urutan'));
		$this->app_name->setDbValue($rs->fields('app_name'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->grp_user_id->DbValue = $row['grp_user_id'];
		$this->tree_id->DbValue = $row['tree_id'];
		$this->level_tree->DbValue = $row['level_tree'];
		$this->com_id->DbValue = $row['com_id'];
		$this->com_form->DbValue = $row['com_form'];
		$this->com_name->DbValue = $row['com_name'];
		$this->caption->DbValue = $row['caption'];
		$this->urutan->DbValue = $row['urutan'];
		$this->app_name->DbValue = $row['app_name'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("com_id")) <> "")
			$this->com_id->CurrentValue = $this->getKey("com_id"); // com_id
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
		// grp_user_id
		// tree_id
		// level_tree
		// com_id
		// com_form
		// com_name
		// caption
		// urutan
		// app_name

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// grp_user_id
		$this->grp_user_id->ViewValue = $this->grp_user_id->CurrentValue;
		$this->grp_user_id->ViewCustomAttributes = "";

		// tree_id
		$this->tree_id->ViewValue = $this->tree_id->CurrentValue;
		$this->tree_id->ViewCustomAttributes = "";

		// level_tree
		$this->level_tree->ViewValue = $this->level_tree->CurrentValue;
		$this->level_tree->ViewCustomAttributes = "";

		// com_id
		$this->com_id->ViewValue = $this->com_id->CurrentValue;
		$this->com_id->ViewCustomAttributes = "";

		// com_form
		$this->com_form->ViewValue = $this->com_form->CurrentValue;
		$this->com_form->ViewCustomAttributes = "";

		// com_name
		$this->com_name->ViewValue = $this->com_name->CurrentValue;
		$this->com_name->ViewCustomAttributes = "";

		// caption
		$this->caption->ViewValue = $this->caption->CurrentValue;
		$this->caption->ViewCustomAttributes = "";

		// urutan
		$this->urutan->ViewValue = $this->urutan->CurrentValue;
		$this->urutan->ViewCustomAttributes = "";

		// app_name
		$this->app_name->ViewValue = $this->app_name->CurrentValue;
		$this->app_name->ViewCustomAttributes = "";

			// grp_user_id
			$this->grp_user_id->LinkCustomAttributes = "";
			$this->grp_user_id->HrefValue = "";
			$this->grp_user_id->TooltipValue = "";

			// tree_id
			$this->tree_id->LinkCustomAttributes = "";
			$this->tree_id->HrefValue = "";
			$this->tree_id->TooltipValue = "";

			// level_tree
			$this->level_tree->LinkCustomAttributes = "";
			$this->level_tree->HrefValue = "";
			$this->level_tree->TooltipValue = "";

			// com_id
			$this->com_id->LinkCustomAttributes = "";
			$this->com_id->HrefValue = "";
			$this->com_id->TooltipValue = "";

			// com_form
			$this->com_form->LinkCustomAttributes = "";
			$this->com_form->HrefValue = "";
			$this->com_form->TooltipValue = "";

			// com_name
			$this->com_name->LinkCustomAttributes = "";
			$this->com_name->HrefValue = "";
			$this->com_name->TooltipValue = "";

			// caption
			$this->caption->LinkCustomAttributes = "";
			$this->caption->HrefValue = "";
			$this->caption->TooltipValue = "";

			// urutan
			$this->urutan->LinkCustomAttributes = "";
			$this->urutan->HrefValue = "";
			$this->urutan->TooltipValue = "";

			// app_name
			$this->app_name->LinkCustomAttributes = "";
			$this->app_name->HrefValue = "";
			$this->app_name->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// grp_user_id
			$this->grp_user_id->EditAttrs["class"] = "form-control";
			$this->grp_user_id->EditCustomAttributes = "";
			$this->grp_user_id->EditValue = ew_HtmlEncode($this->grp_user_id->CurrentValue);
			$this->grp_user_id->PlaceHolder = ew_RemoveHtml($this->grp_user_id->FldCaption());

			// tree_id
			$this->tree_id->EditAttrs["class"] = "form-control";
			$this->tree_id->EditCustomAttributes = "";
			$this->tree_id->EditValue = ew_HtmlEncode($this->tree_id->CurrentValue);
			$this->tree_id->PlaceHolder = ew_RemoveHtml($this->tree_id->FldCaption());

			// level_tree
			$this->level_tree->EditAttrs["class"] = "form-control";
			$this->level_tree->EditCustomAttributes = "";
			$this->level_tree->EditValue = ew_HtmlEncode($this->level_tree->CurrentValue);
			$this->level_tree->PlaceHolder = ew_RemoveHtml($this->level_tree->FldCaption());

			// com_id
			$this->com_id->EditAttrs["class"] = "form-control";
			$this->com_id->EditCustomAttributes = "";
			$this->com_id->EditValue = ew_HtmlEncode($this->com_id->CurrentValue);
			$this->com_id->PlaceHolder = ew_RemoveHtml($this->com_id->FldCaption());

			// com_form
			$this->com_form->EditAttrs["class"] = "form-control";
			$this->com_form->EditCustomAttributes = "";
			$this->com_form->EditValue = ew_HtmlEncode($this->com_form->CurrentValue);
			$this->com_form->PlaceHolder = ew_RemoveHtml($this->com_form->FldCaption());

			// com_name
			$this->com_name->EditAttrs["class"] = "form-control";
			$this->com_name->EditCustomAttributes = "";
			$this->com_name->EditValue = ew_HtmlEncode($this->com_name->CurrentValue);
			$this->com_name->PlaceHolder = ew_RemoveHtml($this->com_name->FldCaption());

			// caption
			$this->caption->EditAttrs["class"] = "form-control";
			$this->caption->EditCustomAttributes = "";
			$this->caption->EditValue = ew_HtmlEncode($this->caption->CurrentValue);
			$this->caption->PlaceHolder = ew_RemoveHtml($this->caption->FldCaption());

			// urutan
			$this->urutan->EditAttrs["class"] = "form-control";
			$this->urutan->EditCustomAttributes = "";
			$this->urutan->EditValue = ew_HtmlEncode($this->urutan->CurrentValue);
			$this->urutan->PlaceHolder = ew_RemoveHtml($this->urutan->FldCaption());

			// app_name
			$this->app_name->EditAttrs["class"] = "form-control";
			$this->app_name->EditCustomAttributes = "";
			$this->app_name->EditValue = ew_HtmlEncode($this->app_name->CurrentValue);
			$this->app_name->PlaceHolder = ew_RemoveHtml($this->app_name->FldCaption());

			// Add refer script
			// grp_user_id

			$this->grp_user_id->LinkCustomAttributes = "";
			$this->grp_user_id->HrefValue = "";

			// tree_id
			$this->tree_id->LinkCustomAttributes = "";
			$this->tree_id->HrefValue = "";

			// level_tree
			$this->level_tree->LinkCustomAttributes = "";
			$this->level_tree->HrefValue = "";

			// com_id
			$this->com_id->LinkCustomAttributes = "";
			$this->com_id->HrefValue = "";

			// com_form
			$this->com_form->LinkCustomAttributes = "";
			$this->com_form->HrefValue = "";

			// com_name
			$this->com_name->LinkCustomAttributes = "";
			$this->com_name->HrefValue = "";

			// caption
			$this->caption->LinkCustomAttributes = "";
			$this->caption->HrefValue = "";

			// urutan
			$this->urutan->LinkCustomAttributes = "";
			$this->urutan->HrefValue = "";

			// app_name
			$this->app_name->LinkCustomAttributes = "";
			$this->app_name->HrefValue = "";
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
		if (!$this->grp_user_id->FldIsDetailKey && !is_null($this->grp_user_id->FormValue) && $this->grp_user_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->grp_user_id->FldCaption(), $this->grp_user_id->ReqErrMsg));
		}
		if (!$this->tree_id->FldIsDetailKey && !is_null($this->tree_id->FormValue) && $this->tree_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tree_id->FldCaption(), $this->tree_id->ReqErrMsg));
		}
		if (!$this->level_tree->FldIsDetailKey && !is_null($this->level_tree->FormValue) && $this->level_tree->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->level_tree->FldCaption(), $this->level_tree->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->level_tree->FormValue)) {
			ew_AddMessage($gsFormError, $this->level_tree->FldErrMsg());
		}
		if (!$this->com_id->FldIsDetailKey && !is_null($this->com_id->FormValue) && $this->com_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->com_id->FldCaption(), $this->com_id->ReqErrMsg));
		}
		if (!$this->com_form->FldIsDetailKey && !is_null($this->com_form->FormValue) && $this->com_form->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->com_form->FldCaption(), $this->com_form->ReqErrMsg));
		}
		if (!$this->com_name->FldIsDetailKey && !is_null($this->com_name->FormValue) && $this->com_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->com_name->FldCaption(), $this->com_name->ReqErrMsg));
		}
		if (!$this->caption->FldIsDetailKey && !is_null($this->caption->FormValue) && $this->caption->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->caption->FldCaption(), $this->caption->ReqErrMsg));
		}
		if (!$this->urutan->FldIsDetailKey && !is_null($this->urutan->FormValue) && $this->urutan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->urutan->FldCaption(), $this->urutan->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->urutan->FormValue)) {
			ew_AddMessage($gsFormError, $this->urutan->FldErrMsg());
		}
		if (!$this->app_name->FldIsDetailKey && !is_null($this->app_name->FormValue) && $this->app_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->app_name->FldCaption(), $this->app_name->ReqErrMsg));
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

		// grp_user_id
		$this->grp_user_id->SetDbValueDef($rsnew, $this->grp_user_id->CurrentValue, "", FALSE);

		// tree_id
		$this->tree_id->SetDbValueDef($rsnew, $this->tree_id->CurrentValue, "", FALSE);

		// level_tree
		$this->level_tree->SetDbValueDef($rsnew, $this->level_tree->CurrentValue, 0, strval($this->level_tree->CurrentValue) == "");

		// com_id
		$this->com_id->SetDbValueDef($rsnew, $this->com_id->CurrentValue, "", FALSE);

		// com_form
		$this->com_form->SetDbValueDef($rsnew, $this->com_form->CurrentValue, "", FALSE);

		// com_name
		$this->com_name->SetDbValueDef($rsnew, $this->com_name->CurrentValue, "", FALSE);

		// caption
		$this->caption->SetDbValueDef($rsnew, $this->caption->CurrentValue, "", FALSE);

		// urutan
		$this->urutan->SetDbValueDef($rsnew, $this->urutan->CurrentValue, 0, FALSE);

		// app_name
		$this->app_name->SetDbValueDef($rsnew, $this->app_name->CurrentValue, "", FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['com_id']) == "") {
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("grp_user_dlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($grp_user_d_add)) $grp_user_d_add = new cgrp_user_d_add();

// Page init
$grp_user_d_add->Page_Init();

// Page main
$grp_user_d_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$grp_user_d_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fgrp_user_dadd = new ew_Form("fgrp_user_dadd", "add");

// Validate form
fgrp_user_dadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_grp_user_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $grp_user_d->grp_user_id->FldCaption(), $grp_user_d->grp_user_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tree_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $grp_user_d->tree_id->FldCaption(), $grp_user_d->tree_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_level_tree");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $grp_user_d->level_tree->FldCaption(), $grp_user_d->level_tree->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_level_tree");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($grp_user_d->level_tree->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_com_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $grp_user_d->com_id->FldCaption(), $grp_user_d->com_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_com_form");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $grp_user_d->com_form->FldCaption(), $grp_user_d->com_form->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_com_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $grp_user_d->com_name->FldCaption(), $grp_user_d->com_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_caption");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $grp_user_d->caption->FldCaption(), $grp_user_d->caption->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_urutan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $grp_user_d->urutan->FldCaption(), $grp_user_d->urutan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_urutan");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($grp_user_d->urutan->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_app_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $grp_user_d->app_name->FldCaption(), $grp_user_d->app_name->ReqErrMsg)) ?>");

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
fgrp_user_dadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgrp_user_dadd.ValidateRequired = true;
<?php } else { ?>
fgrp_user_dadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$grp_user_d_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $grp_user_d_add->ShowPageHeader(); ?>
<?php
$grp_user_d_add->ShowMessage();
?>
<form name="fgrp_user_dadd" id="fgrp_user_dadd" class="<?php echo $grp_user_d_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($grp_user_d_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $grp_user_d_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="grp_user_d">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($grp_user_d_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($grp_user_d->grp_user_id->Visible) { // grp_user_id ?>
	<div id="r_grp_user_id" class="form-group">
		<label id="elh_grp_user_d_grp_user_id" for="x_grp_user_id" class="col-sm-2 control-label ewLabel"><?php echo $grp_user_d->grp_user_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $grp_user_d->grp_user_id->CellAttributes() ?>>
<span id="el_grp_user_d_grp_user_id">
<input type="text" data-table="grp_user_d" data-field="x_grp_user_id" name="x_grp_user_id" id="x_grp_user_id" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($grp_user_d->grp_user_id->getPlaceHolder()) ?>" value="<?php echo $grp_user_d->grp_user_id->EditValue ?>"<?php echo $grp_user_d->grp_user_id->EditAttributes() ?>>
</span>
<?php echo $grp_user_d->grp_user_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($grp_user_d->tree_id->Visible) { // tree_id ?>
	<div id="r_tree_id" class="form-group">
		<label id="elh_grp_user_d_tree_id" for="x_tree_id" class="col-sm-2 control-label ewLabel"><?php echo $grp_user_d->tree_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $grp_user_d->tree_id->CellAttributes() ?>>
<span id="el_grp_user_d_tree_id">
<input type="text" data-table="grp_user_d" data-field="x_tree_id" name="x_tree_id" id="x_tree_id" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($grp_user_d->tree_id->getPlaceHolder()) ?>" value="<?php echo $grp_user_d->tree_id->EditValue ?>"<?php echo $grp_user_d->tree_id->EditAttributes() ?>>
</span>
<?php echo $grp_user_d->tree_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($grp_user_d->level_tree->Visible) { // level_tree ?>
	<div id="r_level_tree" class="form-group">
		<label id="elh_grp_user_d_level_tree" for="x_level_tree" class="col-sm-2 control-label ewLabel"><?php echo $grp_user_d->level_tree->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $grp_user_d->level_tree->CellAttributes() ?>>
<span id="el_grp_user_d_level_tree">
<input type="text" data-table="grp_user_d" data-field="x_level_tree" name="x_level_tree" id="x_level_tree" size="30" placeholder="<?php echo ew_HtmlEncode($grp_user_d->level_tree->getPlaceHolder()) ?>" value="<?php echo $grp_user_d->level_tree->EditValue ?>"<?php echo $grp_user_d->level_tree->EditAttributes() ?>>
</span>
<?php echo $grp_user_d->level_tree->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($grp_user_d->com_id->Visible) { // com_id ?>
	<div id="r_com_id" class="form-group">
		<label id="elh_grp_user_d_com_id" for="x_com_id" class="col-sm-2 control-label ewLabel"><?php echo $grp_user_d->com_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $grp_user_d->com_id->CellAttributes() ?>>
<span id="el_grp_user_d_com_id">
<input type="text" data-table="grp_user_d" data-field="x_com_id" name="x_com_id" id="x_com_id" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($grp_user_d->com_id->getPlaceHolder()) ?>" value="<?php echo $grp_user_d->com_id->EditValue ?>"<?php echo $grp_user_d->com_id->EditAttributes() ?>>
</span>
<?php echo $grp_user_d->com_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($grp_user_d->com_form->Visible) { // com_form ?>
	<div id="r_com_form" class="form-group">
		<label id="elh_grp_user_d_com_form" for="x_com_form" class="col-sm-2 control-label ewLabel"><?php echo $grp_user_d->com_form->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $grp_user_d->com_form->CellAttributes() ?>>
<span id="el_grp_user_d_com_form">
<input type="text" data-table="grp_user_d" data-field="x_com_form" name="x_com_form" id="x_com_form" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($grp_user_d->com_form->getPlaceHolder()) ?>" value="<?php echo $grp_user_d->com_form->EditValue ?>"<?php echo $grp_user_d->com_form->EditAttributes() ?>>
</span>
<?php echo $grp_user_d->com_form->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($grp_user_d->com_name->Visible) { // com_name ?>
	<div id="r_com_name" class="form-group">
		<label id="elh_grp_user_d_com_name" for="x_com_name" class="col-sm-2 control-label ewLabel"><?php echo $grp_user_d->com_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $grp_user_d->com_name->CellAttributes() ?>>
<span id="el_grp_user_d_com_name">
<input type="text" data-table="grp_user_d" data-field="x_com_name" name="x_com_name" id="x_com_name" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($grp_user_d->com_name->getPlaceHolder()) ?>" value="<?php echo $grp_user_d->com_name->EditValue ?>"<?php echo $grp_user_d->com_name->EditAttributes() ?>>
</span>
<?php echo $grp_user_d->com_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($grp_user_d->caption->Visible) { // caption ?>
	<div id="r_caption" class="form-group">
		<label id="elh_grp_user_d_caption" for="x_caption" class="col-sm-2 control-label ewLabel"><?php echo $grp_user_d->caption->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $grp_user_d->caption->CellAttributes() ?>>
<span id="el_grp_user_d_caption">
<input type="text" data-table="grp_user_d" data-field="x_caption" name="x_caption" id="x_caption" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($grp_user_d->caption->getPlaceHolder()) ?>" value="<?php echo $grp_user_d->caption->EditValue ?>"<?php echo $grp_user_d->caption->EditAttributes() ?>>
</span>
<?php echo $grp_user_d->caption->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($grp_user_d->urutan->Visible) { // urutan ?>
	<div id="r_urutan" class="form-group">
		<label id="elh_grp_user_d_urutan" for="x_urutan" class="col-sm-2 control-label ewLabel"><?php echo $grp_user_d->urutan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $grp_user_d->urutan->CellAttributes() ?>>
<span id="el_grp_user_d_urutan">
<input type="text" data-table="grp_user_d" data-field="x_urutan" name="x_urutan" id="x_urutan" size="30" placeholder="<?php echo ew_HtmlEncode($grp_user_d->urutan->getPlaceHolder()) ?>" value="<?php echo $grp_user_d->urutan->EditValue ?>"<?php echo $grp_user_d->urutan->EditAttributes() ?>>
</span>
<?php echo $grp_user_d->urutan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($grp_user_d->app_name->Visible) { // app_name ?>
	<div id="r_app_name" class="form-group">
		<label id="elh_grp_user_d_app_name" for="x_app_name" class="col-sm-2 control-label ewLabel"><?php echo $grp_user_d->app_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $grp_user_d->app_name->CellAttributes() ?>>
<span id="el_grp_user_d_app_name">
<input type="text" data-table="grp_user_d" data-field="x_app_name" name="x_app_name" id="x_app_name" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($grp_user_d->app_name->getPlaceHolder()) ?>" value="<?php echo $grp_user_d->app_name->EditValue ?>"<?php echo $grp_user_d->app_name->EditAttributes() ?>>
</span>
<?php echo $grp_user_d->app_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$grp_user_d_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $grp_user_d_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fgrp_user_dadd.Init();
</script>
<?php
$grp_user_d_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$grp_user_d_add->Page_Terminate();
?>
