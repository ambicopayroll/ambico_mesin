<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "z_pay_cominfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$z_pay_com_edit = NULL; // Initialize page object first

class cz_pay_com_edit extends cz_pay_com {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F36F5C9B-7A33-450D-8126-2253575B79B0}";

	// Table name
	var $TableName = 'z_pay_com';

	// Page object name
	var $PageObjName = 'z_pay_com_edit';

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

		// Table object (z_pay_com)
		if (!isset($GLOBALS["z_pay_com"]) || get_class($GLOBALS["z_pay_com"]) == "cz_pay_com") {
			$GLOBALS["z_pay_com"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["z_pay_com"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'z_pay_com', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("z_pay_comlist.php"));
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
		$this->com_id->SetVisibility();
		$this->com_kode->SetVisibility();
		$this->com_name->SetVisibility();
		$this->type_com->SetVisibility();
		$this->fluctuate->SetVisibility();
		$this->no_urut->SetVisibility();
		$this->param->SetVisibility();
		$this->hitung->SetVisibility();
		$this->dibayar->SetVisibility();
		$this->cara_bayar->SetVisibility();
		$this->pinjaman->SetVisibility();
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
		global $EW_EXPORT, $z_pay_com;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($z_pay_com);
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
		if (@$_GET["com_id"] <> "") {
			$this->com_id->setQueryStringValue($_GET["com_id"]);
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->com_id->CurrentValue == "") {
			$this->Page_Terminate("z_pay_comlist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("z_pay_comlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "z_pay_comlist.php")
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
		if (!$this->com_id->FldIsDetailKey) {
			$this->com_id->setFormValue($objForm->GetValue("x_com_id"));
		}
		if (!$this->com_kode->FldIsDetailKey) {
			$this->com_kode->setFormValue($objForm->GetValue("x_com_kode"));
		}
		if (!$this->com_name->FldIsDetailKey) {
			$this->com_name->setFormValue($objForm->GetValue("x_com_name"));
		}
		if (!$this->type_com->FldIsDetailKey) {
			$this->type_com->setFormValue($objForm->GetValue("x_type_com"));
		}
		if (!$this->fluctuate->FldIsDetailKey) {
			$this->fluctuate->setFormValue($objForm->GetValue("x_fluctuate"));
		}
		if (!$this->no_urut->FldIsDetailKey) {
			$this->no_urut->setFormValue($objForm->GetValue("x_no_urut"));
		}
		if (!$this->param->FldIsDetailKey) {
			$this->param->setFormValue($objForm->GetValue("x_param"));
		}
		if (!$this->hitung->FldIsDetailKey) {
			$this->hitung->setFormValue($objForm->GetValue("x_hitung"));
		}
		if (!$this->dibayar->FldIsDetailKey) {
			$this->dibayar->setFormValue($objForm->GetValue("x_dibayar"));
		}
		if (!$this->cara_bayar->FldIsDetailKey) {
			$this->cara_bayar->setFormValue($objForm->GetValue("x_cara_bayar"));
		}
		if (!$this->pinjaman->FldIsDetailKey) {
			$this->pinjaman->setFormValue($objForm->GetValue("x_pinjaman"));
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
		$this->com_id->CurrentValue = $this->com_id->FormValue;
		$this->com_kode->CurrentValue = $this->com_kode->FormValue;
		$this->com_name->CurrentValue = $this->com_name->FormValue;
		$this->type_com->CurrentValue = $this->type_com->FormValue;
		$this->fluctuate->CurrentValue = $this->fluctuate->FormValue;
		$this->no_urut->CurrentValue = $this->no_urut->FormValue;
		$this->param->CurrentValue = $this->param->FormValue;
		$this->hitung->CurrentValue = $this->hitung->FormValue;
		$this->dibayar->CurrentValue = $this->dibayar->FormValue;
		$this->cara_bayar->CurrentValue = $this->cara_bayar->FormValue;
		$this->pinjaman->CurrentValue = $this->pinjaman->FormValue;
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
		$this->com_id->setDbValue($rs->fields('com_id'));
		$this->com_kode->setDbValue($rs->fields('com_kode'));
		$this->com_name->setDbValue($rs->fields('com_name'));
		$this->type_com->setDbValue($rs->fields('type_com'));
		$this->fluctuate->setDbValue($rs->fields('fluctuate'));
		$this->no_urut->setDbValue($rs->fields('no_urut'));
		$this->param->setDbValue($rs->fields('param'));
		$this->hitung->setDbValue($rs->fields('hitung'));
		$this->dibayar->setDbValue($rs->fields('dibayar'));
		$this->cara_bayar->setDbValue($rs->fields('cara_bayar'));
		$this->pinjaman->setDbValue($rs->fields('pinjaman'));
		$this->lastupdate_date->setDbValue($rs->fields('lastupdate_date'));
		$this->lastupdate_user->setDbValue($rs->fields('lastupdate_user'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->com_id->DbValue = $row['com_id'];
		$this->com_kode->DbValue = $row['com_kode'];
		$this->com_name->DbValue = $row['com_name'];
		$this->type_com->DbValue = $row['type_com'];
		$this->fluctuate->DbValue = $row['fluctuate'];
		$this->no_urut->DbValue = $row['no_urut'];
		$this->param->DbValue = $row['param'];
		$this->hitung->DbValue = $row['hitung'];
		$this->dibayar->DbValue = $row['dibayar'];
		$this->cara_bayar->DbValue = $row['cara_bayar'];
		$this->pinjaman->DbValue = $row['pinjaman'];
		$this->lastupdate_date->DbValue = $row['lastupdate_date'];
		$this->lastupdate_user->DbValue = $row['lastupdate_user'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// com_id
		// com_kode
		// com_name
		// type_com
		// fluctuate
		// no_urut
		// param
		// hitung
		// dibayar
		// cara_bayar
		// pinjaman
		// lastupdate_date
		// lastupdate_user

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// com_id
		$this->com_id->ViewValue = $this->com_id->CurrentValue;
		$this->com_id->ViewCustomAttributes = "";

		// com_kode
		$this->com_kode->ViewValue = $this->com_kode->CurrentValue;
		$this->com_kode->ViewCustomAttributes = "";

		// com_name
		$this->com_name->ViewValue = $this->com_name->CurrentValue;
		$this->com_name->ViewCustomAttributes = "";

		// type_com
		$this->type_com->ViewValue = $this->type_com->CurrentValue;
		$this->type_com->ViewCustomAttributes = "";

		// fluctuate
		$this->fluctuate->ViewValue = $this->fluctuate->CurrentValue;
		$this->fluctuate->ViewCustomAttributes = "";

		// no_urut
		$this->no_urut->ViewValue = $this->no_urut->CurrentValue;
		$this->no_urut->ViewCustomAttributes = "";

		// param
		$this->param->ViewValue = $this->param->CurrentValue;
		$this->param->ViewCustomAttributes = "";

		// hitung
		$this->hitung->ViewValue = $this->hitung->CurrentValue;
		$this->hitung->ViewCustomAttributes = "";

		// dibayar
		$this->dibayar->ViewValue = $this->dibayar->CurrentValue;
		$this->dibayar->ViewCustomAttributes = "";

		// cara_bayar
		$this->cara_bayar->ViewValue = $this->cara_bayar->CurrentValue;
		$this->cara_bayar->ViewCustomAttributes = "";

		// pinjaman
		$this->pinjaman->ViewValue = $this->pinjaman->CurrentValue;
		$this->pinjaman->ViewCustomAttributes = "";

		// lastupdate_date
		$this->lastupdate_date->ViewValue = $this->lastupdate_date->CurrentValue;
		$this->lastupdate_date->ViewValue = ew_FormatDateTime($this->lastupdate_date->ViewValue, 0);
		$this->lastupdate_date->ViewCustomAttributes = "";

		// lastupdate_user
		$this->lastupdate_user->ViewValue = $this->lastupdate_user->CurrentValue;
		$this->lastupdate_user->ViewCustomAttributes = "";

			// com_id
			$this->com_id->LinkCustomAttributes = "";
			$this->com_id->HrefValue = "";
			$this->com_id->TooltipValue = "";

			// com_kode
			$this->com_kode->LinkCustomAttributes = "";
			$this->com_kode->HrefValue = "";
			$this->com_kode->TooltipValue = "";

			// com_name
			$this->com_name->LinkCustomAttributes = "";
			$this->com_name->HrefValue = "";
			$this->com_name->TooltipValue = "";

			// type_com
			$this->type_com->LinkCustomAttributes = "";
			$this->type_com->HrefValue = "";
			$this->type_com->TooltipValue = "";

			// fluctuate
			$this->fluctuate->LinkCustomAttributes = "";
			$this->fluctuate->HrefValue = "";
			$this->fluctuate->TooltipValue = "";

			// no_urut
			$this->no_urut->LinkCustomAttributes = "";
			$this->no_urut->HrefValue = "";
			$this->no_urut->TooltipValue = "";

			// param
			$this->param->LinkCustomAttributes = "";
			$this->param->HrefValue = "";
			$this->param->TooltipValue = "";

			// hitung
			$this->hitung->LinkCustomAttributes = "";
			$this->hitung->HrefValue = "";
			$this->hitung->TooltipValue = "";

			// dibayar
			$this->dibayar->LinkCustomAttributes = "";
			$this->dibayar->HrefValue = "";
			$this->dibayar->TooltipValue = "";

			// cara_bayar
			$this->cara_bayar->LinkCustomAttributes = "";
			$this->cara_bayar->HrefValue = "";
			$this->cara_bayar->TooltipValue = "";

			// pinjaman
			$this->pinjaman->LinkCustomAttributes = "";
			$this->pinjaman->HrefValue = "";
			$this->pinjaman->TooltipValue = "";

			// lastupdate_date
			$this->lastupdate_date->LinkCustomAttributes = "";
			$this->lastupdate_date->HrefValue = "";
			$this->lastupdate_date->TooltipValue = "";

			// lastupdate_user
			$this->lastupdate_user->LinkCustomAttributes = "";
			$this->lastupdate_user->HrefValue = "";
			$this->lastupdate_user->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// com_id
			$this->com_id->EditAttrs["class"] = "form-control";
			$this->com_id->EditCustomAttributes = "";
			$this->com_id->EditValue = $this->com_id->CurrentValue;
			$this->com_id->ViewCustomAttributes = "";

			// com_kode
			$this->com_kode->EditAttrs["class"] = "form-control";
			$this->com_kode->EditCustomAttributes = "";
			$this->com_kode->EditValue = ew_HtmlEncode($this->com_kode->CurrentValue);
			$this->com_kode->PlaceHolder = ew_RemoveHtml($this->com_kode->FldCaption());

			// com_name
			$this->com_name->EditAttrs["class"] = "form-control";
			$this->com_name->EditCustomAttributes = "";
			$this->com_name->EditValue = ew_HtmlEncode($this->com_name->CurrentValue);
			$this->com_name->PlaceHolder = ew_RemoveHtml($this->com_name->FldCaption());

			// type_com
			$this->type_com->EditAttrs["class"] = "form-control";
			$this->type_com->EditCustomAttributes = "";
			$this->type_com->EditValue = ew_HtmlEncode($this->type_com->CurrentValue);
			$this->type_com->PlaceHolder = ew_RemoveHtml($this->type_com->FldCaption());

			// fluctuate
			$this->fluctuate->EditAttrs["class"] = "form-control";
			$this->fluctuate->EditCustomAttributes = "";
			$this->fluctuate->EditValue = ew_HtmlEncode($this->fluctuate->CurrentValue);
			$this->fluctuate->PlaceHolder = ew_RemoveHtml($this->fluctuate->FldCaption());

			// no_urut
			$this->no_urut->EditAttrs["class"] = "form-control";
			$this->no_urut->EditCustomAttributes = "";
			$this->no_urut->EditValue = ew_HtmlEncode($this->no_urut->CurrentValue);
			$this->no_urut->PlaceHolder = ew_RemoveHtml($this->no_urut->FldCaption());

			// param
			$this->param->EditAttrs["class"] = "form-control";
			$this->param->EditCustomAttributes = "";
			$this->param->EditValue = ew_HtmlEncode($this->param->CurrentValue);
			$this->param->PlaceHolder = ew_RemoveHtml($this->param->FldCaption());

			// hitung
			$this->hitung->EditAttrs["class"] = "form-control";
			$this->hitung->EditCustomAttributes = "";
			$this->hitung->EditValue = ew_HtmlEncode($this->hitung->CurrentValue);
			$this->hitung->PlaceHolder = ew_RemoveHtml($this->hitung->FldCaption());

			// dibayar
			$this->dibayar->EditAttrs["class"] = "form-control";
			$this->dibayar->EditCustomAttributes = "";
			$this->dibayar->EditValue = ew_HtmlEncode($this->dibayar->CurrentValue);
			$this->dibayar->PlaceHolder = ew_RemoveHtml($this->dibayar->FldCaption());

			// cara_bayar
			$this->cara_bayar->EditAttrs["class"] = "form-control";
			$this->cara_bayar->EditCustomAttributes = "";
			$this->cara_bayar->EditValue = ew_HtmlEncode($this->cara_bayar->CurrentValue);
			$this->cara_bayar->PlaceHolder = ew_RemoveHtml($this->cara_bayar->FldCaption());

			// pinjaman
			$this->pinjaman->EditAttrs["class"] = "form-control";
			$this->pinjaman->EditCustomAttributes = "";
			$this->pinjaman->EditValue = ew_HtmlEncode($this->pinjaman->CurrentValue);
			$this->pinjaman->PlaceHolder = ew_RemoveHtml($this->pinjaman->FldCaption());

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
			// com_id

			$this->com_id->LinkCustomAttributes = "";
			$this->com_id->HrefValue = "";

			// com_kode
			$this->com_kode->LinkCustomAttributes = "";
			$this->com_kode->HrefValue = "";

			// com_name
			$this->com_name->LinkCustomAttributes = "";
			$this->com_name->HrefValue = "";

			// type_com
			$this->type_com->LinkCustomAttributes = "";
			$this->type_com->HrefValue = "";

			// fluctuate
			$this->fluctuate->LinkCustomAttributes = "";
			$this->fluctuate->HrefValue = "";

			// no_urut
			$this->no_urut->LinkCustomAttributes = "";
			$this->no_urut->HrefValue = "";

			// param
			$this->param->LinkCustomAttributes = "";
			$this->param->HrefValue = "";

			// hitung
			$this->hitung->LinkCustomAttributes = "";
			$this->hitung->HrefValue = "";

			// dibayar
			$this->dibayar->LinkCustomAttributes = "";
			$this->dibayar->HrefValue = "";

			// cara_bayar
			$this->cara_bayar->LinkCustomAttributes = "";
			$this->cara_bayar->HrefValue = "";

			// pinjaman
			$this->pinjaman->LinkCustomAttributes = "";
			$this->pinjaman->HrefValue = "";

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
		if (!$this->com_id->FldIsDetailKey && !is_null($this->com_id->FormValue) && $this->com_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->com_id->FldCaption(), $this->com_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->com_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->com_id->FldErrMsg());
		}
		if (!$this->com_kode->FldIsDetailKey && !is_null($this->com_kode->FormValue) && $this->com_kode->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->com_kode->FldCaption(), $this->com_kode->ReqErrMsg));
		}
		if (!$this->com_name->FldIsDetailKey && !is_null($this->com_name->FormValue) && $this->com_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->com_name->FldCaption(), $this->com_name->ReqErrMsg));
		}
		if (!$this->type_com->FldIsDetailKey && !is_null($this->type_com->FormValue) && $this->type_com->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->type_com->FldCaption(), $this->type_com->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->type_com->FormValue)) {
			ew_AddMessage($gsFormError, $this->type_com->FldErrMsg());
		}
		if (!$this->fluctuate->FldIsDetailKey && !is_null($this->fluctuate->FormValue) && $this->fluctuate->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->fluctuate->FldCaption(), $this->fluctuate->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->fluctuate->FormValue)) {
			ew_AddMessage($gsFormError, $this->fluctuate->FldErrMsg());
		}
		if (!$this->no_urut->FldIsDetailKey && !is_null($this->no_urut->FormValue) && $this->no_urut->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->no_urut->FldCaption(), $this->no_urut->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->no_urut->FormValue)) {
			ew_AddMessage($gsFormError, $this->no_urut->FldErrMsg());
		}
		if (!$this->param->FldIsDetailKey && !is_null($this->param->FormValue) && $this->param->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->param->FldCaption(), $this->param->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->param->FormValue)) {
			ew_AddMessage($gsFormError, $this->param->FldErrMsg());
		}
		if (!$this->hitung->FldIsDetailKey && !is_null($this->hitung->FormValue) && $this->hitung->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->hitung->FldCaption(), $this->hitung->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->hitung->FormValue)) {
			ew_AddMessage($gsFormError, $this->hitung->FldErrMsg());
		}
		if (!$this->dibayar->FldIsDetailKey && !is_null($this->dibayar->FormValue) && $this->dibayar->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->dibayar->FldCaption(), $this->dibayar->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->dibayar->FormValue)) {
			ew_AddMessage($gsFormError, $this->dibayar->FldErrMsg());
		}
		if (!$this->cara_bayar->FldIsDetailKey && !is_null($this->cara_bayar->FormValue) && $this->cara_bayar->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->cara_bayar->FldCaption(), $this->cara_bayar->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->cara_bayar->FormValue)) {
			ew_AddMessage($gsFormError, $this->cara_bayar->FldErrMsg());
		}
		if (!$this->pinjaman->FldIsDetailKey && !is_null($this->pinjaman->FormValue) && $this->pinjaman->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pinjaman->FldCaption(), $this->pinjaman->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->pinjaman->FormValue)) {
			ew_AddMessage($gsFormError, $this->pinjaman->FldErrMsg());
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

			// com_id
			// com_kode

			$this->com_kode->SetDbValueDef($rsnew, $this->com_kode->CurrentValue, "", $this->com_kode->ReadOnly);

			// com_name
			$this->com_name->SetDbValueDef($rsnew, $this->com_name->CurrentValue, "", $this->com_name->ReadOnly);

			// type_com
			$this->type_com->SetDbValueDef($rsnew, $this->type_com->CurrentValue, 0, $this->type_com->ReadOnly);

			// fluctuate
			$this->fluctuate->SetDbValueDef($rsnew, $this->fluctuate->CurrentValue, 0, $this->fluctuate->ReadOnly);

			// no_urut
			$this->no_urut->SetDbValueDef($rsnew, $this->no_urut->CurrentValue, 0, $this->no_urut->ReadOnly);

			// param
			$this->param->SetDbValueDef($rsnew, $this->param->CurrentValue, 0, $this->param->ReadOnly);

			// hitung
			$this->hitung->SetDbValueDef($rsnew, $this->hitung->CurrentValue, 0, $this->hitung->ReadOnly);

			// dibayar
			$this->dibayar->SetDbValueDef($rsnew, $this->dibayar->CurrentValue, 0, $this->dibayar->ReadOnly);

			// cara_bayar
			$this->cara_bayar->SetDbValueDef($rsnew, $this->cara_bayar->CurrentValue, 0, $this->cara_bayar->ReadOnly);

			// pinjaman
			$this->pinjaman->SetDbValueDef($rsnew, $this->pinjaman->CurrentValue, 0, $this->pinjaman->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("z_pay_comlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($z_pay_com_edit)) $z_pay_com_edit = new cz_pay_com_edit();

// Page init
$z_pay_com_edit->Page_Init();

// Page main
$z_pay_com_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$z_pay_com_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fz_pay_comedit = new ew_Form("fz_pay_comedit", "edit");

// Validate form
fz_pay_comedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_com_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_com->com_id->FldCaption(), $z_pay_com->com_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_com_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_com->com_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_com_kode");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_com->com_kode->FldCaption(), $z_pay_com->com_kode->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_com_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_com->com_name->FldCaption(), $z_pay_com->com_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_type_com");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_com->type_com->FldCaption(), $z_pay_com->type_com->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_type_com");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_com->type_com->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fluctuate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_com->fluctuate->FldCaption(), $z_pay_com->fluctuate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fluctuate");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_com->fluctuate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_no_urut");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_com->no_urut->FldCaption(), $z_pay_com->no_urut->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_no_urut");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_com->no_urut->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_param");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_com->param->FldCaption(), $z_pay_com->param->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_param");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_com->param->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_hitung");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_com->hitung->FldCaption(), $z_pay_com->hitung->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_hitung");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_com->hitung->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dibayar");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_com->dibayar->FldCaption(), $z_pay_com->dibayar->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dibayar");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_com->dibayar->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_cara_bayar");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_com->cara_bayar->FldCaption(), $z_pay_com->cara_bayar->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_cara_bayar");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_com->cara_bayar->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pinjaman");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_com->pinjaman->FldCaption(), $z_pay_com->pinjaman->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pinjaman");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_com->pinjaman->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_lastupdate_date");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_com->lastupdate_date->FldCaption(), $z_pay_com->lastupdate_date->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_lastupdate_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_com->lastupdate_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_lastupdate_user");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_com->lastupdate_user->FldCaption(), $z_pay_com->lastupdate_user->ReqErrMsg)) ?>");

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
fz_pay_comedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fz_pay_comedit.ValidateRequired = true;
<?php } else { ?>
fz_pay_comedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$z_pay_com_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $z_pay_com_edit->ShowPageHeader(); ?>
<?php
$z_pay_com_edit->ShowMessage();
?>
<form name="fz_pay_comedit" id="fz_pay_comedit" class="<?php echo $z_pay_com_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($z_pay_com_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $z_pay_com_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="z_pay_com">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($z_pay_com_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($z_pay_com->com_id->Visible) { // com_id ?>
	<div id="r_com_id" class="form-group">
		<label id="elh_z_pay_com_com_id" for="x_com_id" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_com->com_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_com->com_id->CellAttributes() ?>>
<span id="el_z_pay_com_com_id">
<span<?php echo $z_pay_com->com_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $z_pay_com->com_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="z_pay_com" data-field="x_com_id" name="x_com_id" id="x_com_id" value="<?php echo ew_HtmlEncode($z_pay_com->com_id->CurrentValue) ?>">
<?php echo $z_pay_com->com_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_com->com_kode->Visible) { // com_kode ?>
	<div id="r_com_kode" class="form-group">
		<label id="elh_z_pay_com_com_kode" for="x_com_kode" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_com->com_kode->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_com->com_kode->CellAttributes() ?>>
<span id="el_z_pay_com_com_kode">
<input type="text" data-table="z_pay_com" data-field="x_com_kode" name="x_com_kode" id="x_com_kode" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($z_pay_com->com_kode->getPlaceHolder()) ?>" value="<?php echo $z_pay_com->com_kode->EditValue ?>"<?php echo $z_pay_com->com_kode->EditAttributes() ?>>
</span>
<?php echo $z_pay_com->com_kode->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_com->com_name->Visible) { // com_name ?>
	<div id="r_com_name" class="form-group">
		<label id="elh_z_pay_com_com_name" for="x_com_name" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_com->com_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_com->com_name->CellAttributes() ?>>
<span id="el_z_pay_com_com_name">
<input type="text" data-table="z_pay_com" data-field="x_com_name" name="x_com_name" id="x_com_name" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($z_pay_com->com_name->getPlaceHolder()) ?>" value="<?php echo $z_pay_com->com_name->EditValue ?>"<?php echo $z_pay_com->com_name->EditAttributes() ?>>
</span>
<?php echo $z_pay_com->com_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_com->type_com->Visible) { // type_com ?>
	<div id="r_type_com" class="form-group">
		<label id="elh_z_pay_com_type_com" for="x_type_com" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_com->type_com->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_com->type_com->CellAttributes() ?>>
<span id="el_z_pay_com_type_com">
<input type="text" data-table="z_pay_com" data-field="x_type_com" name="x_type_com" id="x_type_com" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_com->type_com->getPlaceHolder()) ?>" value="<?php echo $z_pay_com->type_com->EditValue ?>"<?php echo $z_pay_com->type_com->EditAttributes() ?>>
</span>
<?php echo $z_pay_com->type_com->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_com->fluctuate->Visible) { // fluctuate ?>
	<div id="r_fluctuate" class="form-group">
		<label id="elh_z_pay_com_fluctuate" for="x_fluctuate" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_com->fluctuate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_com->fluctuate->CellAttributes() ?>>
<span id="el_z_pay_com_fluctuate">
<input type="text" data-table="z_pay_com" data-field="x_fluctuate" name="x_fluctuate" id="x_fluctuate" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_com->fluctuate->getPlaceHolder()) ?>" value="<?php echo $z_pay_com->fluctuate->EditValue ?>"<?php echo $z_pay_com->fluctuate->EditAttributes() ?>>
</span>
<?php echo $z_pay_com->fluctuate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_com->no_urut->Visible) { // no_urut ?>
	<div id="r_no_urut" class="form-group">
		<label id="elh_z_pay_com_no_urut" for="x_no_urut" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_com->no_urut->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_com->no_urut->CellAttributes() ?>>
<span id="el_z_pay_com_no_urut">
<input type="text" data-table="z_pay_com" data-field="x_no_urut" name="x_no_urut" id="x_no_urut" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_com->no_urut->getPlaceHolder()) ?>" value="<?php echo $z_pay_com->no_urut->EditValue ?>"<?php echo $z_pay_com->no_urut->EditAttributes() ?>>
</span>
<?php echo $z_pay_com->no_urut->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_com->param->Visible) { // param ?>
	<div id="r_param" class="form-group">
		<label id="elh_z_pay_com_param" for="x_param" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_com->param->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_com->param->CellAttributes() ?>>
<span id="el_z_pay_com_param">
<input type="text" data-table="z_pay_com" data-field="x_param" name="x_param" id="x_param" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_com->param->getPlaceHolder()) ?>" value="<?php echo $z_pay_com->param->EditValue ?>"<?php echo $z_pay_com->param->EditAttributes() ?>>
</span>
<?php echo $z_pay_com->param->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_com->hitung->Visible) { // hitung ?>
	<div id="r_hitung" class="form-group">
		<label id="elh_z_pay_com_hitung" for="x_hitung" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_com->hitung->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_com->hitung->CellAttributes() ?>>
<span id="el_z_pay_com_hitung">
<input type="text" data-table="z_pay_com" data-field="x_hitung" name="x_hitung" id="x_hitung" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_com->hitung->getPlaceHolder()) ?>" value="<?php echo $z_pay_com->hitung->EditValue ?>"<?php echo $z_pay_com->hitung->EditAttributes() ?>>
</span>
<?php echo $z_pay_com->hitung->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_com->dibayar->Visible) { // dibayar ?>
	<div id="r_dibayar" class="form-group">
		<label id="elh_z_pay_com_dibayar" for="x_dibayar" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_com->dibayar->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_com->dibayar->CellAttributes() ?>>
<span id="el_z_pay_com_dibayar">
<input type="text" data-table="z_pay_com" data-field="x_dibayar" name="x_dibayar" id="x_dibayar" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_com->dibayar->getPlaceHolder()) ?>" value="<?php echo $z_pay_com->dibayar->EditValue ?>"<?php echo $z_pay_com->dibayar->EditAttributes() ?>>
</span>
<?php echo $z_pay_com->dibayar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_com->cara_bayar->Visible) { // cara_bayar ?>
	<div id="r_cara_bayar" class="form-group">
		<label id="elh_z_pay_com_cara_bayar" for="x_cara_bayar" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_com->cara_bayar->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_com->cara_bayar->CellAttributes() ?>>
<span id="el_z_pay_com_cara_bayar">
<input type="text" data-table="z_pay_com" data-field="x_cara_bayar" name="x_cara_bayar" id="x_cara_bayar" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_com->cara_bayar->getPlaceHolder()) ?>" value="<?php echo $z_pay_com->cara_bayar->EditValue ?>"<?php echo $z_pay_com->cara_bayar->EditAttributes() ?>>
</span>
<?php echo $z_pay_com->cara_bayar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_com->pinjaman->Visible) { // pinjaman ?>
	<div id="r_pinjaman" class="form-group">
		<label id="elh_z_pay_com_pinjaman" for="x_pinjaman" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_com->pinjaman->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_com->pinjaman->CellAttributes() ?>>
<span id="el_z_pay_com_pinjaman">
<input type="text" data-table="z_pay_com" data-field="x_pinjaman" name="x_pinjaman" id="x_pinjaman" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_com->pinjaman->getPlaceHolder()) ?>" value="<?php echo $z_pay_com->pinjaman->EditValue ?>"<?php echo $z_pay_com->pinjaman->EditAttributes() ?>>
</span>
<?php echo $z_pay_com->pinjaman->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_com->lastupdate_date->Visible) { // lastupdate_date ?>
	<div id="r_lastupdate_date" class="form-group">
		<label id="elh_z_pay_com_lastupdate_date" for="x_lastupdate_date" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_com->lastupdate_date->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_com->lastupdate_date->CellAttributes() ?>>
<span id="el_z_pay_com_lastupdate_date">
<input type="text" data-table="z_pay_com" data-field="x_lastupdate_date" name="x_lastupdate_date" id="x_lastupdate_date" placeholder="<?php echo ew_HtmlEncode($z_pay_com->lastupdate_date->getPlaceHolder()) ?>" value="<?php echo $z_pay_com->lastupdate_date->EditValue ?>"<?php echo $z_pay_com->lastupdate_date->EditAttributes() ?>>
</span>
<?php echo $z_pay_com->lastupdate_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_com->lastupdate_user->Visible) { // lastupdate_user ?>
	<div id="r_lastupdate_user" class="form-group">
		<label id="elh_z_pay_com_lastupdate_user" for="x_lastupdate_user" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_com->lastupdate_user->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_com->lastupdate_user->CellAttributes() ?>>
<span id="el_z_pay_com_lastupdate_user">
<input type="text" data-table="z_pay_com" data-field="x_lastupdate_user" name="x_lastupdate_user" id="x_lastupdate_user" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($z_pay_com->lastupdate_user->getPlaceHolder()) ?>" value="<?php echo $z_pay_com->lastupdate_user->EditValue ?>"<?php echo $z_pay_com->lastupdate_user->EditAttributes() ?>>
</span>
<?php echo $z_pay_com->lastupdate_user->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$z_pay_com_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $z_pay_com_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fz_pay_comedit.Init();
</script>
<?php
$z_pay_com_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$z_pay_com_edit->Page_Terminate();
?>
