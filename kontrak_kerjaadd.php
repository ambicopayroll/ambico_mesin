<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "kontrak_kerjainfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$kontrak_kerja_add = NULL; // Initialize page object first

class ckontrak_kerja_add extends ckontrak_kerja {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F36F5C9B-7A33-450D-8126-2253575B79B0}";

	// Table name
	var $TableName = 'kontrak_kerja';

	// Page object name
	var $PageObjName = 'kontrak_kerja_add';

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

		// Table object (kontrak_kerja)
		if (!isset($GLOBALS["kontrak_kerja"]) || get_class($GLOBALS["kontrak_kerja"]) == "ckontrak_kerja") {
			$GLOBALS["kontrak_kerja"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["kontrak_kerja"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'kontrak_kerja', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("kontrak_kerjalist.php"));
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
		$this->pegawai_id->SetVisibility();
		$this->kontrak_start->SetVisibility();
		$this->kontrak_end->SetVisibility();
		$this->kontrak_status->SetVisibility();
		$this->kontrak_aktif->SetVisibility();

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
		global $EW_EXPORT, $kontrak_kerja;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($kontrak_kerja);
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
			if (@$_GET["pegawai_id"] != "") {
				$this->pegawai_id->setQueryStringValue($_GET["pegawai_id"]);
				$this->setKey("pegawai_id", $this->pegawai_id->CurrentValue); // Set up key
			} else {
				$this->setKey("pegawai_id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["kontrak_start"] != "") {
				$this->kontrak_start->setQueryStringValue($_GET["kontrak_start"]);
				$this->setKey("kontrak_start", $this->kontrak_start->CurrentValue); // Set up key
			} else {
				$this->setKey("kontrak_start", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["kontrak_end"] != "") {
				$this->kontrak_end->setQueryStringValue($_GET["kontrak_end"]);
				$this->setKey("kontrak_end", $this->kontrak_end->CurrentValue); // Set up key
			} else {
				$this->setKey("kontrak_end", ""); // Clear key
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
					$this->Page_Terminate("kontrak_kerjalist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "kontrak_kerjalist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "kontrak_kerjaview.php")
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
		$this->pegawai_id->CurrentValue = 0;
		$this->kontrak_start->CurrentValue = "0000-00-00";
		$this->kontrak_end->CurrentValue = "0000-00-00";
		$this->kontrak_status->CurrentValue = 0;
		$this->kontrak_aktif->CurrentValue = -1;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->pegawai_id->FldIsDetailKey) {
			$this->pegawai_id->setFormValue($objForm->GetValue("x_pegawai_id"));
		}
		if (!$this->kontrak_start->FldIsDetailKey) {
			$this->kontrak_start->setFormValue($objForm->GetValue("x_kontrak_start"));
			$this->kontrak_start->CurrentValue = ew_UnFormatDateTime($this->kontrak_start->CurrentValue, 0);
		}
		if (!$this->kontrak_end->FldIsDetailKey) {
			$this->kontrak_end->setFormValue($objForm->GetValue("x_kontrak_end"));
			$this->kontrak_end->CurrentValue = ew_UnFormatDateTime($this->kontrak_end->CurrentValue, 0);
		}
		if (!$this->kontrak_status->FldIsDetailKey) {
			$this->kontrak_status->setFormValue($objForm->GetValue("x_kontrak_status"));
		}
		if (!$this->kontrak_aktif->FldIsDetailKey) {
			$this->kontrak_aktif->setFormValue($objForm->GetValue("x_kontrak_aktif"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->pegawai_id->CurrentValue = $this->pegawai_id->FormValue;
		$this->kontrak_start->CurrentValue = $this->kontrak_start->FormValue;
		$this->kontrak_start->CurrentValue = ew_UnFormatDateTime($this->kontrak_start->CurrentValue, 0);
		$this->kontrak_end->CurrentValue = $this->kontrak_end->FormValue;
		$this->kontrak_end->CurrentValue = ew_UnFormatDateTime($this->kontrak_end->CurrentValue, 0);
		$this->kontrak_status->CurrentValue = $this->kontrak_status->FormValue;
		$this->kontrak_aktif->CurrentValue = $this->kontrak_aktif->FormValue;
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
		$this->pegawai_id->setDbValue($rs->fields('pegawai_id'));
		$this->kontrak_start->setDbValue($rs->fields('kontrak_start'));
		$this->kontrak_end->setDbValue($rs->fields('kontrak_end'));
		$this->kontrak_status->setDbValue($rs->fields('kontrak_status'));
		$this->kontrak_aktif->setDbValue($rs->fields('kontrak_aktif'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->pegawai_id->DbValue = $row['pegawai_id'];
		$this->kontrak_start->DbValue = $row['kontrak_start'];
		$this->kontrak_end->DbValue = $row['kontrak_end'];
		$this->kontrak_status->DbValue = $row['kontrak_status'];
		$this->kontrak_aktif->DbValue = $row['kontrak_aktif'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("pegawai_id")) <> "")
			$this->pegawai_id->CurrentValue = $this->getKey("pegawai_id"); // pegawai_id
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("kontrak_start")) <> "")
			$this->kontrak_start->CurrentValue = $this->getKey("kontrak_start"); // kontrak_start
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("kontrak_end")) <> "")
			$this->kontrak_end->CurrentValue = $this->getKey("kontrak_end"); // kontrak_end
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
		// pegawai_id
		// kontrak_start
		// kontrak_end
		// kontrak_status
		// kontrak_aktif

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// pegawai_id
		$this->pegawai_id->ViewValue = $this->pegawai_id->CurrentValue;
		$this->pegawai_id->ViewCustomAttributes = "";

		// kontrak_start
		$this->kontrak_start->ViewValue = $this->kontrak_start->CurrentValue;
		$this->kontrak_start->ViewValue = ew_FormatDateTime($this->kontrak_start->ViewValue, 0);
		$this->kontrak_start->ViewCustomAttributes = "";

		// kontrak_end
		$this->kontrak_end->ViewValue = $this->kontrak_end->CurrentValue;
		$this->kontrak_end->ViewValue = ew_FormatDateTime($this->kontrak_end->ViewValue, 0);
		$this->kontrak_end->ViewCustomAttributes = "";

		// kontrak_status
		$this->kontrak_status->ViewValue = $this->kontrak_status->CurrentValue;
		$this->kontrak_status->ViewCustomAttributes = "";

		// kontrak_aktif
		$this->kontrak_aktif->ViewValue = $this->kontrak_aktif->CurrentValue;
		$this->kontrak_aktif->ViewCustomAttributes = "";

			// pegawai_id
			$this->pegawai_id->LinkCustomAttributes = "";
			$this->pegawai_id->HrefValue = "";
			$this->pegawai_id->TooltipValue = "";

			// kontrak_start
			$this->kontrak_start->LinkCustomAttributes = "";
			$this->kontrak_start->HrefValue = "";
			$this->kontrak_start->TooltipValue = "";

			// kontrak_end
			$this->kontrak_end->LinkCustomAttributes = "";
			$this->kontrak_end->HrefValue = "";
			$this->kontrak_end->TooltipValue = "";

			// kontrak_status
			$this->kontrak_status->LinkCustomAttributes = "";
			$this->kontrak_status->HrefValue = "";
			$this->kontrak_status->TooltipValue = "";

			// kontrak_aktif
			$this->kontrak_aktif->LinkCustomAttributes = "";
			$this->kontrak_aktif->HrefValue = "";
			$this->kontrak_aktif->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// pegawai_id
			$this->pegawai_id->EditAttrs["class"] = "form-control";
			$this->pegawai_id->EditCustomAttributes = "";
			$this->pegawai_id->EditValue = ew_HtmlEncode($this->pegawai_id->CurrentValue);
			$this->pegawai_id->PlaceHolder = ew_RemoveHtml($this->pegawai_id->FldCaption());

			// kontrak_start
			$this->kontrak_start->EditAttrs["class"] = "form-control";
			$this->kontrak_start->EditCustomAttributes = "";
			$this->kontrak_start->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->kontrak_start->CurrentValue, 8));
			$this->kontrak_start->PlaceHolder = ew_RemoveHtml($this->kontrak_start->FldCaption());

			// kontrak_end
			$this->kontrak_end->EditAttrs["class"] = "form-control";
			$this->kontrak_end->EditCustomAttributes = "";
			$this->kontrak_end->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->kontrak_end->CurrentValue, 8));
			$this->kontrak_end->PlaceHolder = ew_RemoveHtml($this->kontrak_end->FldCaption());

			// kontrak_status
			$this->kontrak_status->EditAttrs["class"] = "form-control";
			$this->kontrak_status->EditCustomAttributes = "";
			$this->kontrak_status->EditValue = ew_HtmlEncode($this->kontrak_status->CurrentValue);
			$this->kontrak_status->PlaceHolder = ew_RemoveHtml($this->kontrak_status->FldCaption());

			// kontrak_aktif
			$this->kontrak_aktif->EditAttrs["class"] = "form-control";
			$this->kontrak_aktif->EditCustomAttributes = "";
			$this->kontrak_aktif->EditValue = ew_HtmlEncode($this->kontrak_aktif->CurrentValue);
			$this->kontrak_aktif->PlaceHolder = ew_RemoveHtml($this->kontrak_aktif->FldCaption());

			// Add refer script
			// pegawai_id

			$this->pegawai_id->LinkCustomAttributes = "";
			$this->pegawai_id->HrefValue = "";

			// kontrak_start
			$this->kontrak_start->LinkCustomAttributes = "";
			$this->kontrak_start->HrefValue = "";

			// kontrak_end
			$this->kontrak_end->LinkCustomAttributes = "";
			$this->kontrak_end->HrefValue = "";

			// kontrak_status
			$this->kontrak_status->LinkCustomAttributes = "";
			$this->kontrak_status->HrefValue = "";

			// kontrak_aktif
			$this->kontrak_aktif->LinkCustomAttributes = "";
			$this->kontrak_aktif->HrefValue = "";
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
		if (!$this->pegawai_id->FldIsDetailKey && !is_null($this->pegawai_id->FormValue) && $this->pegawai_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pegawai_id->FldCaption(), $this->pegawai_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->pegawai_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->pegawai_id->FldErrMsg());
		}
		if (!$this->kontrak_start->FldIsDetailKey && !is_null($this->kontrak_start->FormValue) && $this->kontrak_start->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->kontrak_start->FldCaption(), $this->kontrak_start->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->kontrak_start->FormValue)) {
			ew_AddMessage($gsFormError, $this->kontrak_start->FldErrMsg());
		}
		if (!$this->kontrak_end->FldIsDetailKey && !is_null($this->kontrak_end->FormValue) && $this->kontrak_end->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->kontrak_end->FldCaption(), $this->kontrak_end->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->kontrak_end->FormValue)) {
			ew_AddMessage($gsFormError, $this->kontrak_end->FldErrMsg());
		}
		if (!ew_CheckInteger($this->kontrak_status->FormValue)) {
			ew_AddMessage($gsFormError, $this->kontrak_status->FldErrMsg());
		}
		if (!ew_CheckInteger($this->kontrak_aktif->FormValue)) {
			ew_AddMessage($gsFormError, $this->kontrak_aktif->FldErrMsg());
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

		// pegawai_id
		$this->pegawai_id->SetDbValueDef($rsnew, $this->pegawai_id->CurrentValue, 0, strval($this->pegawai_id->CurrentValue) == "");

		// kontrak_start
		$this->kontrak_start->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->kontrak_start->CurrentValue, 0), ew_CurrentDate(), strval($this->kontrak_start->CurrentValue) == "");

		// kontrak_end
		$this->kontrak_end->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->kontrak_end->CurrentValue, 0), ew_CurrentDate(), strval($this->kontrak_end->CurrentValue) == "");

		// kontrak_status
		$this->kontrak_status->SetDbValueDef($rsnew, $this->kontrak_status->CurrentValue, NULL, strval($this->kontrak_status->CurrentValue) == "");

		// kontrak_aktif
		$this->kontrak_aktif->SetDbValueDef($rsnew, $this->kontrak_aktif->CurrentValue, NULL, strval($this->kontrak_aktif->CurrentValue) == "");

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['pegawai_id']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['kontrak_start']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['kontrak_end']) == "") {
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("kontrak_kerjalist.php"), "", $this->TableVar, TRUE);
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
if (!isset($kontrak_kerja_add)) $kontrak_kerja_add = new ckontrak_kerja_add();

// Page init
$kontrak_kerja_add->Page_Init();

// Page main
$kontrak_kerja_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$kontrak_kerja_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fkontrak_kerjaadd = new ew_Form("fkontrak_kerjaadd", "add");

// Validate form
fkontrak_kerjaadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_pegawai_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $kontrak_kerja->pegawai_id->FldCaption(), $kontrak_kerja->pegawai_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pegawai_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($kontrak_kerja->pegawai_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_kontrak_start");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $kontrak_kerja->kontrak_start->FldCaption(), $kontrak_kerja->kontrak_start->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_kontrak_start");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($kontrak_kerja->kontrak_start->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_kontrak_end");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $kontrak_kerja->kontrak_end->FldCaption(), $kontrak_kerja->kontrak_end->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_kontrak_end");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($kontrak_kerja->kontrak_end->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_kontrak_status");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($kontrak_kerja->kontrak_status->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_kontrak_aktif");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($kontrak_kerja->kontrak_aktif->FldErrMsg()) ?>");

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
fkontrak_kerjaadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fkontrak_kerjaadd.ValidateRequired = true;
<?php } else { ?>
fkontrak_kerjaadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$kontrak_kerja_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $kontrak_kerja_add->ShowPageHeader(); ?>
<?php
$kontrak_kerja_add->ShowMessage();
?>
<form name="fkontrak_kerjaadd" id="fkontrak_kerjaadd" class="<?php echo $kontrak_kerja_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($kontrak_kerja_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $kontrak_kerja_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="kontrak_kerja">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($kontrak_kerja_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($kontrak_kerja->pegawai_id->Visible) { // pegawai_id ?>
	<div id="r_pegawai_id" class="form-group">
		<label id="elh_kontrak_kerja_pegawai_id" for="x_pegawai_id" class="col-sm-2 control-label ewLabel"><?php echo $kontrak_kerja->pegawai_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $kontrak_kerja->pegawai_id->CellAttributes() ?>>
<span id="el_kontrak_kerja_pegawai_id">
<input type="text" data-table="kontrak_kerja" data-field="x_pegawai_id" name="x_pegawai_id" id="x_pegawai_id" size="30" placeholder="<?php echo ew_HtmlEncode($kontrak_kerja->pegawai_id->getPlaceHolder()) ?>" value="<?php echo $kontrak_kerja->pegawai_id->EditValue ?>"<?php echo $kontrak_kerja->pegawai_id->EditAttributes() ?>>
</span>
<?php echo $kontrak_kerja->pegawai_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($kontrak_kerja->kontrak_start->Visible) { // kontrak_start ?>
	<div id="r_kontrak_start" class="form-group">
		<label id="elh_kontrak_kerja_kontrak_start" for="x_kontrak_start" class="col-sm-2 control-label ewLabel"><?php echo $kontrak_kerja->kontrak_start->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $kontrak_kerja->kontrak_start->CellAttributes() ?>>
<span id="el_kontrak_kerja_kontrak_start">
<input type="text" data-table="kontrak_kerja" data-field="x_kontrak_start" name="x_kontrak_start" id="x_kontrak_start" placeholder="<?php echo ew_HtmlEncode($kontrak_kerja->kontrak_start->getPlaceHolder()) ?>" value="<?php echo $kontrak_kerja->kontrak_start->EditValue ?>"<?php echo $kontrak_kerja->kontrak_start->EditAttributes() ?>>
</span>
<?php echo $kontrak_kerja->kontrak_start->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($kontrak_kerja->kontrak_end->Visible) { // kontrak_end ?>
	<div id="r_kontrak_end" class="form-group">
		<label id="elh_kontrak_kerja_kontrak_end" for="x_kontrak_end" class="col-sm-2 control-label ewLabel"><?php echo $kontrak_kerja->kontrak_end->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $kontrak_kerja->kontrak_end->CellAttributes() ?>>
<span id="el_kontrak_kerja_kontrak_end">
<input type="text" data-table="kontrak_kerja" data-field="x_kontrak_end" name="x_kontrak_end" id="x_kontrak_end" placeholder="<?php echo ew_HtmlEncode($kontrak_kerja->kontrak_end->getPlaceHolder()) ?>" value="<?php echo $kontrak_kerja->kontrak_end->EditValue ?>"<?php echo $kontrak_kerja->kontrak_end->EditAttributes() ?>>
</span>
<?php echo $kontrak_kerja->kontrak_end->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($kontrak_kerja->kontrak_status->Visible) { // kontrak_status ?>
	<div id="r_kontrak_status" class="form-group">
		<label id="elh_kontrak_kerja_kontrak_status" for="x_kontrak_status" class="col-sm-2 control-label ewLabel"><?php echo $kontrak_kerja->kontrak_status->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $kontrak_kerja->kontrak_status->CellAttributes() ?>>
<span id="el_kontrak_kerja_kontrak_status">
<input type="text" data-table="kontrak_kerja" data-field="x_kontrak_status" name="x_kontrak_status" id="x_kontrak_status" size="30" placeholder="<?php echo ew_HtmlEncode($kontrak_kerja->kontrak_status->getPlaceHolder()) ?>" value="<?php echo $kontrak_kerja->kontrak_status->EditValue ?>"<?php echo $kontrak_kerja->kontrak_status->EditAttributes() ?>>
</span>
<?php echo $kontrak_kerja->kontrak_status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($kontrak_kerja->kontrak_aktif->Visible) { // kontrak_aktif ?>
	<div id="r_kontrak_aktif" class="form-group">
		<label id="elh_kontrak_kerja_kontrak_aktif" for="x_kontrak_aktif" class="col-sm-2 control-label ewLabel"><?php echo $kontrak_kerja->kontrak_aktif->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $kontrak_kerja->kontrak_aktif->CellAttributes() ?>>
<span id="el_kontrak_kerja_kontrak_aktif">
<input type="text" data-table="kontrak_kerja" data-field="x_kontrak_aktif" name="x_kontrak_aktif" id="x_kontrak_aktif" size="30" placeholder="<?php echo ew_HtmlEncode($kontrak_kerja->kontrak_aktif->getPlaceHolder()) ?>" value="<?php echo $kontrak_kerja->kontrak_aktif->EditValue ?>"<?php echo $kontrak_kerja->kontrak_aktif->EditAttributes() ?>>
</span>
<?php echo $kontrak_kerja->kontrak_aktif->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$kontrak_kerja_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $kontrak_kerja_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fkontrak_kerjaadd.Init();
</script>
<?php
$kontrak_kerja_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$kontrak_kerja_add->Page_Terminate();
?>
