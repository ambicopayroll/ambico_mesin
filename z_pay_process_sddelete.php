<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "z_pay_process_sdinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$z_pay_process_sd_delete = NULL; // Initialize page object first

class cz_pay_process_sd_delete extends cz_pay_process_sd {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F36F5C9B-7A33-450D-8126-2253575B79B0}";

	// Table name
	var $TableName = 'z_pay_process_sd';

	// Page object name
	var $PageObjName = 'z_pay_process_sd_delete';

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

		// Table object (z_pay_process_sd)
		if (!isset($GLOBALS["z_pay_process_sd"]) || get_class($GLOBALS["z_pay_process_sd"]) == "cz_pay_process_sd") {
			$GLOBALS["z_pay_process_sd"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["z_pay_process_sd"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'z_pay_process_sd', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("z_pay_process_sdlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->process_id->SetVisibility();
		$this->no_urut->SetVisibility();
		$this->no_urut_ref->SetVisibility();
		$this->emp_id_auto->SetVisibility();
		$this->com_id->SetVisibility();
		$this->kondisi->SetVisibility();
		$this->rumus->SetVisibility();
		$this->subtot_payroll->SetVisibility();
		$this->jml_faktor->SetVisibility();

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
		global $EW_EXPORT, $z_pay_process_sd;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($z_pay_process_sd);
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
			$this->Page_Terminate("z_pay_process_sdlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in z_pay_process_sd class, z_pay_process_sdinfo.php

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
				$this->Page_Terminate("z_pay_process_sdlist.php"); // Return to list
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
		$this->process_id->setDbValue($rs->fields('process_id'));
		$this->no_urut->setDbValue($rs->fields('no_urut'));
		$this->no_urut_ref->setDbValue($rs->fields('no_urut_ref'));
		$this->emp_id_auto->setDbValue($rs->fields('emp_id_auto'));
		$this->com_id->setDbValue($rs->fields('com_id'));
		$this->kondisi->setDbValue($rs->fields('kondisi'));
		$this->rumus->setDbValue($rs->fields('rumus'));
		$this->subtot_payroll->setDbValue($rs->fields('subtot_payroll'));
		$this->jml_faktor->setDbValue($rs->fields('jml_faktor'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->process_id->DbValue = $row['process_id'];
		$this->no_urut->DbValue = $row['no_urut'];
		$this->no_urut_ref->DbValue = $row['no_urut_ref'];
		$this->emp_id_auto->DbValue = $row['emp_id_auto'];
		$this->com_id->DbValue = $row['com_id'];
		$this->kondisi->DbValue = $row['kondisi'];
		$this->rumus->DbValue = $row['rumus'];
		$this->subtot_payroll->DbValue = $row['subtot_payroll'];
		$this->jml_faktor->DbValue = $row['jml_faktor'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->subtot_payroll->FormValue == $this->subtot_payroll->CurrentValue && is_numeric(ew_StrToFloat($this->subtot_payroll->CurrentValue)))
			$this->subtot_payroll->CurrentValue = ew_StrToFloat($this->subtot_payroll->CurrentValue);

		// Convert decimal values if posted back
		if ($this->jml_faktor->FormValue == $this->jml_faktor->CurrentValue && is_numeric(ew_StrToFloat($this->jml_faktor->CurrentValue)))
			$this->jml_faktor->CurrentValue = ew_StrToFloat($this->jml_faktor->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// process_id
		// no_urut
		// no_urut_ref
		// emp_id_auto
		// com_id
		// kondisi
		// rumus
		// subtot_payroll
		// jml_faktor

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// process_id
		$this->process_id->ViewValue = $this->process_id->CurrentValue;
		$this->process_id->ViewCustomAttributes = "";

		// no_urut
		$this->no_urut->ViewValue = $this->no_urut->CurrentValue;
		$this->no_urut->ViewCustomAttributes = "";

		// no_urut_ref
		$this->no_urut_ref->ViewValue = $this->no_urut_ref->CurrentValue;
		$this->no_urut_ref->ViewCustomAttributes = "";

		// emp_id_auto
		$this->emp_id_auto->ViewValue = $this->emp_id_auto->CurrentValue;
		$this->emp_id_auto->ViewCustomAttributes = "";

		// com_id
		$this->com_id->ViewValue = $this->com_id->CurrentValue;
		$this->com_id->ViewCustomAttributes = "";

		// kondisi
		$this->kondisi->ViewValue = $this->kondisi->CurrentValue;
		$this->kondisi->ViewCustomAttributes = "";

		// rumus
		$this->rumus->ViewValue = $this->rumus->CurrentValue;
		$this->rumus->ViewCustomAttributes = "";

		// subtot_payroll
		$this->subtot_payroll->ViewValue = $this->subtot_payroll->CurrentValue;
		$this->subtot_payroll->ViewCustomAttributes = "";

		// jml_faktor
		$this->jml_faktor->ViewValue = $this->jml_faktor->CurrentValue;
		$this->jml_faktor->ViewCustomAttributes = "";

			// process_id
			$this->process_id->LinkCustomAttributes = "";
			$this->process_id->HrefValue = "";
			$this->process_id->TooltipValue = "";

			// no_urut
			$this->no_urut->LinkCustomAttributes = "";
			$this->no_urut->HrefValue = "";
			$this->no_urut->TooltipValue = "";

			// no_urut_ref
			$this->no_urut_ref->LinkCustomAttributes = "";
			$this->no_urut_ref->HrefValue = "";
			$this->no_urut_ref->TooltipValue = "";

			// emp_id_auto
			$this->emp_id_auto->LinkCustomAttributes = "";
			$this->emp_id_auto->HrefValue = "";
			$this->emp_id_auto->TooltipValue = "";

			// com_id
			$this->com_id->LinkCustomAttributes = "";
			$this->com_id->HrefValue = "";
			$this->com_id->TooltipValue = "";

			// kondisi
			$this->kondisi->LinkCustomAttributes = "";
			$this->kondisi->HrefValue = "";
			$this->kondisi->TooltipValue = "";

			// rumus
			$this->rumus->LinkCustomAttributes = "";
			$this->rumus->HrefValue = "";
			$this->rumus->TooltipValue = "";

			// subtot_payroll
			$this->subtot_payroll->LinkCustomAttributes = "";
			$this->subtot_payroll->HrefValue = "";
			$this->subtot_payroll->TooltipValue = "";

			// jml_faktor
			$this->jml_faktor->LinkCustomAttributes = "";
			$this->jml_faktor->HrefValue = "";
			$this->jml_faktor->TooltipValue = "";
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
				$sThisKey .= $row['process_id'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['no_urut'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['no_urut_ref'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("z_pay_process_sdlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($z_pay_process_sd_delete)) $z_pay_process_sd_delete = new cz_pay_process_sd_delete();

// Page init
$z_pay_process_sd_delete->Page_Init();

// Page main
$z_pay_process_sd_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$z_pay_process_sd_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fz_pay_process_sddelete = new ew_Form("fz_pay_process_sddelete", "delete");

// Form_CustomValidate event
fz_pay_process_sddelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fz_pay_process_sddelete.ValidateRequired = true;
<?php } else { ?>
fz_pay_process_sddelete.ValidateRequired = false; 
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
<?php $z_pay_process_sd_delete->ShowPageHeader(); ?>
<?php
$z_pay_process_sd_delete->ShowMessage();
?>
<form name="fz_pay_process_sddelete" id="fz_pay_process_sddelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($z_pay_process_sd_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $z_pay_process_sd_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="z_pay_process_sd">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($z_pay_process_sd_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $z_pay_process_sd->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($z_pay_process_sd->process_id->Visible) { // process_id ?>
		<th><span id="elh_z_pay_process_sd_process_id" class="z_pay_process_sd_process_id"><?php echo $z_pay_process_sd->process_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($z_pay_process_sd->no_urut->Visible) { // no_urut ?>
		<th><span id="elh_z_pay_process_sd_no_urut" class="z_pay_process_sd_no_urut"><?php echo $z_pay_process_sd->no_urut->FldCaption() ?></span></th>
<?php } ?>
<?php if ($z_pay_process_sd->no_urut_ref->Visible) { // no_urut_ref ?>
		<th><span id="elh_z_pay_process_sd_no_urut_ref" class="z_pay_process_sd_no_urut_ref"><?php echo $z_pay_process_sd->no_urut_ref->FldCaption() ?></span></th>
<?php } ?>
<?php if ($z_pay_process_sd->emp_id_auto->Visible) { // emp_id_auto ?>
		<th><span id="elh_z_pay_process_sd_emp_id_auto" class="z_pay_process_sd_emp_id_auto"><?php echo $z_pay_process_sd->emp_id_auto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($z_pay_process_sd->com_id->Visible) { // com_id ?>
		<th><span id="elh_z_pay_process_sd_com_id" class="z_pay_process_sd_com_id"><?php echo $z_pay_process_sd->com_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($z_pay_process_sd->kondisi->Visible) { // kondisi ?>
		<th><span id="elh_z_pay_process_sd_kondisi" class="z_pay_process_sd_kondisi"><?php echo $z_pay_process_sd->kondisi->FldCaption() ?></span></th>
<?php } ?>
<?php if ($z_pay_process_sd->rumus->Visible) { // rumus ?>
		<th><span id="elh_z_pay_process_sd_rumus" class="z_pay_process_sd_rumus"><?php echo $z_pay_process_sd->rumus->FldCaption() ?></span></th>
<?php } ?>
<?php if ($z_pay_process_sd->subtot_payroll->Visible) { // subtot_payroll ?>
		<th><span id="elh_z_pay_process_sd_subtot_payroll" class="z_pay_process_sd_subtot_payroll"><?php echo $z_pay_process_sd->subtot_payroll->FldCaption() ?></span></th>
<?php } ?>
<?php if ($z_pay_process_sd->jml_faktor->Visible) { // jml_faktor ?>
		<th><span id="elh_z_pay_process_sd_jml_faktor" class="z_pay_process_sd_jml_faktor"><?php echo $z_pay_process_sd->jml_faktor->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$z_pay_process_sd_delete->RecCnt = 0;
$i = 0;
while (!$z_pay_process_sd_delete->Recordset->EOF) {
	$z_pay_process_sd_delete->RecCnt++;
	$z_pay_process_sd_delete->RowCnt++;

	// Set row properties
	$z_pay_process_sd->ResetAttrs();
	$z_pay_process_sd->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$z_pay_process_sd_delete->LoadRowValues($z_pay_process_sd_delete->Recordset);

	// Render row
	$z_pay_process_sd_delete->RenderRow();
?>
	<tr<?php echo $z_pay_process_sd->RowAttributes() ?>>
<?php if ($z_pay_process_sd->process_id->Visible) { // process_id ?>
		<td<?php echo $z_pay_process_sd->process_id->CellAttributes() ?>>
<span id="el<?php echo $z_pay_process_sd_delete->RowCnt ?>_z_pay_process_sd_process_id" class="z_pay_process_sd_process_id">
<span<?php echo $z_pay_process_sd->process_id->ViewAttributes() ?>>
<?php echo $z_pay_process_sd->process_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($z_pay_process_sd->no_urut->Visible) { // no_urut ?>
		<td<?php echo $z_pay_process_sd->no_urut->CellAttributes() ?>>
<span id="el<?php echo $z_pay_process_sd_delete->RowCnt ?>_z_pay_process_sd_no_urut" class="z_pay_process_sd_no_urut">
<span<?php echo $z_pay_process_sd->no_urut->ViewAttributes() ?>>
<?php echo $z_pay_process_sd->no_urut->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($z_pay_process_sd->no_urut_ref->Visible) { // no_urut_ref ?>
		<td<?php echo $z_pay_process_sd->no_urut_ref->CellAttributes() ?>>
<span id="el<?php echo $z_pay_process_sd_delete->RowCnt ?>_z_pay_process_sd_no_urut_ref" class="z_pay_process_sd_no_urut_ref">
<span<?php echo $z_pay_process_sd->no_urut_ref->ViewAttributes() ?>>
<?php echo $z_pay_process_sd->no_urut_ref->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($z_pay_process_sd->emp_id_auto->Visible) { // emp_id_auto ?>
		<td<?php echo $z_pay_process_sd->emp_id_auto->CellAttributes() ?>>
<span id="el<?php echo $z_pay_process_sd_delete->RowCnt ?>_z_pay_process_sd_emp_id_auto" class="z_pay_process_sd_emp_id_auto">
<span<?php echo $z_pay_process_sd->emp_id_auto->ViewAttributes() ?>>
<?php echo $z_pay_process_sd->emp_id_auto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($z_pay_process_sd->com_id->Visible) { // com_id ?>
		<td<?php echo $z_pay_process_sd->com_id->CellAttributes() ?>>
<span id="el<?php echo $z_pay_process_sd_delete->RowCnt ?>_z_pay_process_sd_com_id" class="z_pay_process_sd_com_id">
<span<?php echo $z_pay_process_sd->com_id->ViewAttributes() ?>>
<?php echo $z_pay_process_sd->com_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($z_pay_process_sd->kondisi->Visible) { // kondisi ?>
		<td<?php echo $z_pay_process_sd->kondisi->CellAttributes() ?>>
<span id="el<?php echo $z_pay_process_sd_delete->RowCnt ?>_z_pay_process_sd_kondisi" class="z_pay_process_sd_kondisi">
<span<?php echo $z_pay_process_sd->kondisi->ViewAttributes() ?>>
<?php echo $z_pay_process_sd->kondisi->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($z_pay_process_sd->rumus->Visible) { // rumus ?>
		<td<?php echo $z_pay_process_sd->rumus->CellAttributes() ?>>
<span id="el<?php echo $z_pay_process_sd_delete->RowCnt ?>_z_pay_process_sd_rumus" class="z_pay_process_sd_rumus">
<span<?php echo $z_pay_process_sd->rumus->ViewAttributes() ?>>
<?php echo $z_pay_process_sd->rumus->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($z_pay_process_sd->subtot_payroll->Visible) { // subtot_payroll ?>
		<td<?php echo $z_pay_process_sd->subtot_payroll->CellAttributes() ?>>
<span id="el<?php echo $z_pay_process_sd_delete->RowCnt ?>_z_pay_process_sd_subtot_payroll" class="z_pay_process_sd_subtot_payroll">
<span<?php echo $z_pay_process_sd->subtot_payroll->ViewAttributes() ?>>
<?php echo $z_pay_process_sd->subtot_payroll->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($z_pay_process_sd->jml_faktor->Visible) { // jml_faktor ?>
		<td<?php echo $z_pay_process_sd->jml_faktor->CellAttributes() ?>>
<span id="el<?php echo $z_pay_process_sd_delete->RowCnt ?>_z_pay_process_sd_jml_faktor" class="z_pay_process_sd_jml_faktor">
<span<?php echo $z_pay_process_sd->jml_faktor->ViewAttributes() ?>>
<?php echo $z_pay_process_sd->jml_faktor->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$z_pay_process_sd_delete->Recordset->MoveNext();
}
$z_pay_process_sd_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $z_pay_process_sd_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fz_pay_process_sddelete.Init();
</script>
<?php
$z_pay_process_sd_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$z_pay_process_sd_delete->Page_Terminate();
?>