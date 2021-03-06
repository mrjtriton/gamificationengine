<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "helper_ewcfg11.php" ?>
<?php include_once "helper_ewmysql11.php" ?>
<?php include_once "helper_phpfn11.php" ?>
<?php include_once "helper_user_aktivitasinfo.php" ?>
<?php include_once "helper_userfn11.php" ?>
<?php

//
// Page class
//

$user_aktivitas_add = NULL; // Initialize page object first

class cuser_aktivitas_add extends cuser_aktivitas {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{1E4BA4B9-446C-4642-AFD1-C346E6CE346F}";

	// Table name
	var $TableName = 'user_aktivitas';

	// Page object name
	var $PageObjName = 'user_aktivitas_add';

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

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
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
			return $fn($_POST[EW_TOKEN_NAME]);
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
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (user_aktivitas)
		if (!isset($GLOBALS["user_aktivitas"]) || get_class($GLOBALS["user_aktivitas"]) == "cuser_aktivitas") {
			$GLOBALS["user_aktivitas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["user_aktivitas"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'user_aktivitas', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

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
		global $conn, $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $user_aktivitas;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($user_aktivitas);
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
		$conn->Close();

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
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["user_id"] != "") {
				$this->user_id->setQueryStringValue($_GET["user_id"]);
				$this->setKey("user_id", $this->user_id->CurrentValue); // Set up key
			} else {
				$this->setKey("user_id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["aktivitas_id"] != "") {
				$this->aktivitas_id->setQueryStringValue($_GET["aktivitas_id"]);
				$this->setKey("aktivitas_id", $this->aktivitas_id->CurrentValue); // Set up key
			} else {
				$this->setKey("aktivitas_id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
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
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("helper_user_aktivitaslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "helper_user_aktivitasview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

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
		$this->user_id->CurrentValue = NULL;
		$this->user_id->OldValue = $this->user_id->CurrentValue;
		$this->aktivitas_id->CurrentValue = NULL;
		$this->aktivitas_id->OldValue = $this->aktivitas_id->CurrentValue;
		$this->poin->CurrentValue = NULL;
		$this->poin->OldValue = $this->poin->CurrentValue;
		$this->insert_at->CurrentValue = NULL;
		$this->insert_at->OldValue = $this->insert_at->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->user_id->FldIsDetailKey) {
			$this->user_id->setFormValue($objForm->GetValue("x_user_id"));
		}
		if (!$this->aktivitas_id->FldIsDetailKey) {
			$this->aktivitas_id->setFormValue($objForm->GetValue("x_aktivitas_id"));
		}
		if (!$this->poin->FldIsDetailKey) {
			$this->poin->setFormValue($objForm->GetValue("x_poin"));
		}
		if (!$this->insert_at->FldIsDetailKey) {
			$this->insert_at->setFormValue($objForm->GetValue("x_insert_at"));
			$this->insert_at->CurrentValue = ew_UnFormatDateTime($this->insert_at->CurrentValue, 5);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->user_id->CurrentValue = $this->user_id->FormValue;
		$this->aktivitas_id->CurrentValue = $this->aktivitas_id->FormValue;
		$this->poin->CurrentValue = $this->poin->FormValue;
		$this->insert_at->CurrentValue = $this->insert_at->FormValue;
		$this->insert_at->CurrentValue = ew_UnFormatDateTime($this->insert_at->CurrentValue, 5);
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->user_id->setDbValue($rs->fields('user_id'));
		$this->aktivitas_id->setDbValue($rs->fields('aktivitas_id'));
		$this->poin->setDbValue($rs->fields('poin'));
		$this->insert_at->setDbValue($rs->fields('insert_at'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->user_id->DbValue = $row['user_id'];
		$this->aktivitas_id->DbValue = $row['aktivitas_id'];
		$this->poin->DbValue = $row['poin'];
		$this->insert_at->DbValue = $row['insert_at'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("user_id")) <> "")
			$this->user_id->CurrentValue = $this->getKey("user_id"); // user_id
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("aktivitas_id")) <> "")
			$this->aktivitas_id->CurrentValue = $this->getKey("aktivitas_id"); // aktivitas_id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->poin->FormValue == $this->poin->CurrentValue && is_numeric(ew_StrToFloat($this->poin->CurrentValue)))
			$this->poin->CurrentValue = ew_StrToFloat($this->poin->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// user_id
		// aktivitas_id
		// poin
		// insert_at

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// user_id
			$this->user_id->ViewValue = $this->user_id->CurrentValue;
			$this->user_id->ViewCustomAttributes = "";

			// aktivitas_id
			$this->aktivitas_id->ViewValue = $this->aktivitas_id->CurrentValue;
			$this->aktivitas_id->ViewCustomAttributes = "";

			// poin
			$this->poin->ViewValue = $this->poin->CurrentValue;
			$this->poin->ViewCustomAttributes = "";

			// insert_at
			$this->insert_at->ViewValue = $this->insert_at->CurrentValue;
			$this->insert_at->ViewValue = ew_FormatDateTime($this->insert_at->ViewValue, 5);
			$this->insert_at->ViewCustomAttributes = "";

			// user_id
			$this->user_id->LinkCustomAttributes = "";
			$this->user_id->HrefValue = "";
			$this->user_id->TooltipValue = "";

			// aktivitas_id
			$this->aktivitas_id->LinkCustomAttributes = "";
			$this->aktivitas_id->HrefValue = "";
			$this->aktivitas_id->TooltipValue = "";

			// poin
			$this->poin->LinkCustomAttributes = "";
			$this->poin->HrefValue = "";
			$this->poin->TooltipValue = "";

			// insert_at
			$this->insert_at->LinkCustomAttributes = "";
			$this->insert_at->HrefValue = "";
			$this->insert_at->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// user_id
			$this->user_id->EditAttrs["class"] = "form-control";
			$this->user_id->EditCustomAttributes = "";
			$this->user_id->EditValue = ew_HtmlEncode($this->user_id->CurrentValue);
			$this->user_id->PlaceHolder = ew_RemoveHtml($this->user_id->FldCaption());

			// aktivitas_id
			$this->aktivitas_id->EditAttrs["class"] = "form-control";
			$this->aktivitas_id->EditCustomAttributes = "";
			$this->aktivitas_id->EditValue = ew_HtmlEncode($this->aktivitas_id->CurrentValue);
			$this->aktivitas_id->PlaceHolder = ew_RemoveHtml($this->aktivitas_id->FldCaption());

			// poin
			$this->poin->EditAttrs["class"] = "form-control";
			$this->poin->EditCustomAttributes = "";
			$this->poin->EditValue = ew_HtmlEncode($this->poin->CurrentValue);
			$this->poin->PlaceHolder = ew_RemoveHtml($this->poin->FldCaption());
			if (strval($this->poin->EditValue) <> "" && is_numeric($this->poin->EditValue)) $this->poin->EditValue = ew_FormatNumber($this->poin->EditValue, -2, -1, -2, 0);

			// insert_at
			$this->insert_at->EditAttrs["class"] = "form-control";
			$this->insert_at->EditCustomAttributes = "";
			$this->insert_at->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->insert_at->CurrentValue, 5));
			$this->insert_at->PlaceHolder = ew_RemoveHtml($this->insert_at->FldCaption());

			// Edit refer script
			// user_id

			$this->user_id->HrefValue = "";

			// aktivitas_id
			$this->aktivitas_id->HrefValue = "";

			// poin
			$this->poin->HrefValue = "";

			// insert_at
			$this->insert_at->HrefValue = "";
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
		if (!$this->user_id->FldIsDetailKey && !is_null($this->user_id->FormValue) && $this->user_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->user_id->FldCaption(), $this->user_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->user_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->user_id->FldErrMsg());
		}
		if (!$this->aktivitas_id->FldIsDetailKey && !is_null($this->aktivitas_id->FormValue) && $this->aktivitas_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->aktivitas_id->FldCaption(), $this->aktivitas_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->aktivitas_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->aktivitas_id->FldErrMsg());
		}
		if (!$this->poin->FldIsDetailKey && !is_null($this->poin->FormValue) && $this->poin->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->poin->FldCaption(), $this->poin->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->poin->FormValue)) {
			ew_AddMessage($gsFormError, $this->poin->FldErrMsg());
		}
		if (!ew_CheckDate($this->insert_at->FormValue)) {
			ew_AddMessage($gsFormError, $this->insert_at->FldErrMsg());
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
		global $conn, $Language, $Security;

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// user_id
		$this->user_id->SetDbValueDef($rsnew, $this->user_id->CurrentValue, 0, FALSE);

		// aktivitas_id
		$this->aktivitas_id->SetDbValueDef($rsnew, $this->aktivitas_id->CurrentValue, 0, FALSE);

		// poin
		$this->poin->SetDbValueDef($rsnew, $this->poin->CurrentValue, 0, FALSE);

		// insert_at
		$this->insert_at->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->insert_at->CurrentValue, 5), NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['user_id']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['aktivitas_id']) == "") {
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

		// Get insert id if necessary
		if ($AddRow) {
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
		$Breadcrumb->Add("list", $this->TableVar, "helper_user_aktivitaslist.php", "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($user_aktivitas_add)) $user_aktivitas_add = new cuser_aktivitas_add();

// Page init
$user_aktivitas_add->Page_Init();

// Page main
$user_aktivitas_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$user_aktivitas_add->Page_Render();
?>
<?php include_once "helper_header.php" ?>
<script type="text/javascript">

// Page object
var user_aktivitas_add = new ew_Page("user_aktivitas_add");
user_aktivitas_add.PageID = "add"; // Page ID
var EW_PAGE_ID = user_aktivitas_add.PageID; // For backward compatibility

// Form object
var fuser_aktivitasadd = new ew_Form("fuser_aktivitasadd");

// Validate form
fuser_aktivitasadd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
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
			elm = this.GetElements("x" + infix + "_user_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user_aktivitas->user_id->FldCaption(), $user_aktivitas->user_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_user_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($user_aktivitas->user_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_aktivitas_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user_aktivitas->aktivitas_id->FldCaption(), $user_aktivitas->aktivitas_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_aktivitas_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($user_aktivitas->aktivitas_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_poin");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user_aktivitas->poin->FldCaption(), $user_aktivitas->poin->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_poin");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($user_aktivitas->poin->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_insert_at");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($user_aktivitas->insert_at->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

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
fuser_aktivitasadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fuser_aktivitasadd.ValidateRequired = true;
<?php } else { ?>
fuser_aktivitasadd.ValidateRequired = false; 
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
<?php $user_aktivitas_add->ShowPageHeader(); ?>
<?php
$user_aktivitas_add->ShowMessage();
?>
<form name="fuser_aktivitasadd" id="fuser_aktivitasadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($user_aktivitas_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $user_aktivitas_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="user_aktivitas">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($user_aktivitas->user_id->Visible) { // user_id ?>
	<div id="r_user_id" class="form-group">
		<label id="elh_user_aktivitas_user_id" for="x_user_id" class="col-sm-2 control-label ewLabel"><?php echo $user_aktivitas->user_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $user_aktivitas->user_id->CellAttributes() ?>>
<span id="el_user_aktivitas_user_id">
<input type="text" data-field="x_user_id" name="x_user_id" id="x_user_id" size="30" placeholder="<?php echo ew_HtmlEncode($user_aktivitas->user_id->PlaceHolder) ?>" value="<?php echo $user_aktivitas->user_id->EditValue ?>"<?php echo $user_aktivitas->user_id->EditAttributes() ?>>
</span>
<?php echo $user_aktivitas->user_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user_aktivitas->aktivitas_id->Visible) { // aktivitas_id ?>
	<div id="r_aktivitas_id" class="form-group">
		<label id="elh_user_aktivitas_aktivitas_id" for="x_aktivitas_id" class="col-sm-2 control-label ewLabel"><?php echo $user_aktivitas->aktivitas_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $user_aktivitas->aktivitas_id->CellAttributes() ?>>
<span id="el_user_aktivitas_aktivitas_id">
<input type="text" data-field="x_aktivitas_id" name="x_aktivitas_id" id="x_aktivitas_id" size="30" placeholder="<?php echo ew_HtmlEncode($user_aktivitas->aktivitas_id->PlaceHolder) ?>" value="<?php echo $user_aktivitas->aktivitas_id->EditValue ?>"<?php echo $user_aktivitas->aktivitas_id->EditAttributes() ?>>
</span>
<?php echo $user_aktivitas->aktivitas_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user_aktivitas->poin->Visible) { // poin ?>
	<div id="r_poin" class="form-group">
		<label id="elh_user_aktivitas_poin" for="x_poin" class="col-sm-2 control-label ewLabel"><?php echo $user_aktivitas->poin->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $user_aktivitas->poin->CellAttributes() ?>>
<span id="el_user_aktivitas_poin">
<input type="text" data-field="x_poin" name="x_poin" id="x_poin" size="30" placeholder="<?php echo ew_HtmlEncode($user_aktivitas->poin->PlaceHolder) ?>" value="<?php echo $user_aktivitas->poin->EditValue ?>"<?php echo $user_aktivitas->poin->EditAttributes() ?>>
</span>
<?php echo $user_aktivitas->poin->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user_aktivitas->insert_at->Visible) { // insert_at ?>
	<div id="r_insert_at" class="form-group">
		<label id="elh_user_aktivitas_insert_at" for="x_insert_at" class="col-sm-2 control-label ewLabel"><?php echo $user_aktivitas->insert_at->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $user_aktivitas->insert_at->CellAttributes() ?>>
<span id="el_user_aktivitas_insert_at">
<input type="text" data-field="x_insert_at" name="x_insert_at" id="x_insert_at" placeholder="<?php echo ew_HtmlEncode($user_aktivitas->insert_at->PlaceHolder) ?>" value="<?php echo $user_aktivitas->insert_at->EditValue ?>"<?php echo $user_aktivitas->insert_at->EditAttributes() ?>>
</span>
<?php echo $user_aktivitas->insert_at->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fuser_aktivitasadd.Init();
</script>
<?php
$user_aktivitas_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "helper_footer.php" ?>
<?php
$user_aktivitas_add->Page_Terminate();
?>
