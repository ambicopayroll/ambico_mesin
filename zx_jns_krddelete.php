<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "zx_jns_krdinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$zx_jns_krd_delete = NULL; // Initialize page object first

class czx_jns_krd_delete extends czx_jns_krd {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{0B4A4F9E-7A2B-4234-9791-3975C1DCDDA1}";

	// Table name
	var $TableName = 'zx_jns_krd';

	// Page object name
	var $PageObjName = 'zx_jns_krd_delete';

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

		// Table object (zx_jns_krd)
		if (!isset($GLOBALS["zx_jns_krd"]) || get_class($GLOBALS["zx_jns_krd"]) == "czx_jns_krd") {
			$GLOBALS["zx_jns_krd"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["zx_jns_krd"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'zx_jns_krd', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("zx_jns_krdlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->krd_id->SetVisibility();
		$this->krd_kode->SetVisibility();
		$this->krd_name->SetVisibility();
		$this->com_id->SetVisibility();
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
		global $EW_EXPORT, $zx_jns_krd;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($zx_jns_krd);
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
			$this->Page_Terminate("zx_jns_krdlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in zx_jns_krd class, zx_jns_krdinfo.php

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
				$this->Page_Terminate("zx_jns_krdlist.php"); // Return to list
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
		$this->krd_id->setDbValue($rs->fields('krd_id'));
		$this->krd_kode->setDbValue($rs->fields('krd_kode'));
		$this->krd_name->setDbValue($rs->fields('krd_name'));
		$this->com_id->setDbValue($rs->fields('com_id'));
		$this->lastupdate_date->setDbValue($rs->fields('lastupdate_date'));
		$this->lastupdate_user->setDbValue($rs->fields('lastupdate_user'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->krd_id->DbValue = $row['krd_id'];
		$this->krd_kode->DbValue = $row['krd_kode'];
		$this->krd_name->DbValue = $row['krd_name'];
		$this->com_id->DbValue = $row['com_id'];
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
		// krd_id
		// krd_kode
		// krd_name
		// com_id
		// lastupdate_date
		// lastupdate_user

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// krd_id
		$this->krd_id->ViewValue = $this->krd_id->CurrentValue;
		$this->krd_id->ViewCustomAttributes = "";

		// krd_kode
		$this->krd_kode->ViewValue = $this->krd_kode->CurrentValue;
		$this->krd_kode->ViewCustomAttributes = "";

		// krd_name
		$this->krd_name->ViewValue = $this->krd_name->CurrentValue;
		$this->krd_name->ViewCustomAttributes = "";

		// com_id
		$this->com_id->ViewValue = $this->com_id->CurrentValue;
		$this->com_id->ViewCustomAttributes = "";

		// lastupdate_date
		$this->lastupdate_date->ViewValue = $this->lastupdate_date->CurrentValue;
		$this->lastupdate_date->ViewValue = ew_FormatDateTime($this->lastupdate_date->ViewValue, 0);
		$this->lastupdate_date->ViewCustomAttributes = "";

		// lastupdate_user
		$this->lastupdate_user->ViewValue = $this->lastupdate_user->CurrentValue;
		$this->lastupdate_user->ViewCustomAttributes = "";

			// krd_id
			$this->krd_id->LinkCustomAttributes = "";
			$this->krd_id->HrefValue = "";
			$this->krd_id->TooltipValue = "";

			// krd_kode
			$this->krd_kode->LinkCustomAttributes = "";
			$this->krd_kode->HrefValue = "";
			$this->krd_kode->TooltipValue = "";

			// krd_name
			$this->krd_name->LinkCustomAttributes = "";
			$this->krd_name->HrefValue = "";
			$this->krd_name->TooltipValue = "";

			// com_id
			$this->com_id->LinkCustomAttributes = "";
			$this->com_id->HrefValue = "";
			$this->com_id->TooltipValue = "";

			// lastupdate_date
			$this->lastupdate_date->LinkCustomAttributes = "";
			$this->lastupdate_date->HrefValue = "";
			$this->lastupdate_date->TooltipValue = "";

			// lastupdate_user
			$this->lastupdate_user->LinkCustomAttributes = "";
			$this->lastupdate_user->HrefValue = "";
			$this->lastupdate_user->TooltipValue = "";
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
				$sThisKey .= $row['krd_id'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("zx_jns_krdlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($zx_jns_krd_delete)) $zx_jns_krd_delete = new czx_jns_krd_delete();

// Page init
$zx_jns_krd_delete->Page_Init();

// Page main
$zx_jns_krd_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$zx_jns_krd_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fzx_jns_krddelete = new ew_Form("fzx_jns_krddelete", "delete");

// Form_CustomValidate event
fzx_jns_krddelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fzx_jns_krddelete.ValidateRequired = true;
<?php } else { ?>
fzx_jns_krddelete.ValidateRequired = false; 
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
<?php $zx_jns_krd_delete->ShowPageHeader(); ?>
<?php
$zx_jns_krd_delete->ShowMessage();
?>
<form name="fzx_jns_krddelete" id="fzx_jns_krddelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($zx_jns_krd_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $zx_jns_krd_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="zx_jns_krd">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($zx_jns_krd_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $zx_jns_krd->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($zx_jns_krd->krd_id->Visible) { // krd_id ?>
		<th><span id="elh_zx_jns_krd_krd_id" class="zx_jns_krd_krd_id"><?php echo $zx_jns_krd->krd_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_jns_krd->krd_kode->Visible) { // krd_kode ?>
		<th><span id="elh_zx_jns_krd_krd_kode" class="zx_jns_krd_krd_kode"><?php echo $zx_jns_krd->krd_kode->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_jns_krd->krd_name->Visible) { // krd_name ?>
		<th><span id="elh_zx_jns_krd_krd_name" class="zx_jns_krd_krd_name"><?php echo $zx_jns_krd->krd_name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_jns_krd->com_id->Visible) { // com_id ?>
		<th><span id="elh_zx_jns_krd_com_id" class="zx_jns_krd_com_id"><?php echo $zx_jns_krd->com_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_jns_krd->lastupdate_date->Visible) { // lastupdate_date ?>
		<th><span id="elh_zx_jns_krd_lastupdate_date" class="zx_jns_krd_lastupdate_date"><?php echo $zx_jns_krd->lastupdate_date->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_jns_krd->lastupdate_user->Visible) { // lastupdate_user ?>
		<th><span id="elh_zx_jns_krd_lastupdate_user" class="zx_jns_krd_lastupdate_user"><?php echo $zx_jns_krd->lastupdate_user->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$zx_jns_krd_delete->RecCnt = 0;
$i = 0;
while (!$zx_jns_krd_delete->Recordset->EOF) {
	$zx_jns_krd_delete->RecCnt++;
	$zx_jns_krd_delete->RowCnt++;

	// Set row properties
	$zx_jns_krd->ResetAttrs();
	$zx_jns_krd->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$zx_jns_krd_delete->LoadRowValues($zx_jns_krd_delete->Recordset);

	// Render row
	$zx_jns_krd_delete->RenderRow();
?>
	<tr<?php echo $zx_jns_krd->RowAttributes() ?>>
<?php if ($zx_jns_krd->krd_id->Visible) { // krd_id ?>
		<td<?php echo $zx_jns_krd->krd_id->CellAttributes() ?>>
<span id="el<?php echo $zx_jns_krd_delete->RowCnt ?>_zx_jns_krd_krd_id" class="zx_jns_krd_krd_id">
<span<?php echo $zx_jns_krd->krd_id->ViewAttributes() ?>>
<?php echo $zx_jns_krd->krd_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_jns_krd->krd_kode->Visible) { // krd_kode ?>
		<td<?php echo $zx_jns_krd->krd_kode->CellAttributes() ?>>
<span id="el<?php echo $zx_jns_krd_delete->RowCnt ?>_zx_jns_krd_krd_kode" class="zx_jns_krd_krd_kode">
<span<?php echo $zx_jns_krd->krd_kode->ViewAttributes() ?>>
<?php echo $zx_jns_krd->krd_kode->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_jns_krd->krd_name->Visible) { // krd_name ?>
		<td<?php echo $zx_jns_krd->krd_name->CellAttributes() ?>>
<span id="el<?php echo $zx_jns_krd_delete->RowCnt ?>_zx_jns_krd_krd_name" class="zx_jns_krd_krd_name">
<span<?php echo $zx_jns_krd->krd_name->ViewAttributes() ?>>
<?php echo $zx_jns_krd->krd_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_jns_krd->com_id->Visible) { // com_id ?>
		<td<?php echo $zx_jns_krd->com_id->CellAttributes() ?>>
<span id="el<?php echo $zx_jns_krd_delete->RowCnt ?>_zx_jns_krd_com_id" class="zx_jns_krd_com_id">
<span<?php echo $zx_jns_krd->com_id->ViewAttributes() ?>>
<?php echo $zx_jns_krd->com_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_jns_krd->lastupdate_date->Visible) { // lastupdate_date ?>
		<td<?php echo $zx_jns_krd->lastupdate_date->CellAttributes() ?>>
<span id="el<?php echo $zx_jns_krd_delete->RowCnt ?>_zx_jns_krd_lastupdate_date" class="zx_jns_krd_lastupdate_date">
<span<?php echo $zx_jns_krd->lastupdate_date->ViewAttributes() ?>>
<?php echo $zx_jns_krd->lastupdate_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_jns_krd->lastupdate_user->Visible) { // lastupdate_user ?>
		<td<?php echo $zx_jns_krd->lastupdate_user->CellAttributes() ?>>
<span id="el<?php echo $zx_jns_krd_delete->RowCnt ?>_zx_jns_krd_lastupdate_user" class="zx_jns_krd_lastupdate_user">
<span<?php echo $zx_jns_krd->lastupdate_user->ViewAttributes() ?>>
<?php echo $zx_jns_krd->lastupdate_user->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$zx_jns_krd_delete->Recordset->MoveNext();
}
$zx_jns_krd_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $zx_jns_krd_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fzx_jns_krddelete.Init();
</script>
<?php
$zx_jns_krd_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$zx_jns_krd_delete->Page_Terminate();
?>
