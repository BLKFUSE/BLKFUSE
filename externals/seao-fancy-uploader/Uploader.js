/*
---

name: SeaoFancyUploader

description: Crossbrowser file uploader with HTML5 chunk upload support, flexible UI and nice modability. Uploads are based on Mooupload by Juan Lago

requires: [Core/Class, Core/Object, Core/Element.Event, Core/Fx.Elements, Core/Fx.Tween]

provides: [SeaoFancyUploader]

...
*/

var SeaoFancyUploader = new Class({

	Implements: [Options, Events],

	options: {
		
		// UI Elements
		
		/* 
		The class accomodates to use what's available:
		- eg. if ui_list is defined, uploaded items will be output into it, otherwise this functionality will be disabled
		- ui_button OR ui_drop_area is required to select files
		- drop area only works in HTML5 mode
		- drop area and ui_list can be the same element
		- drop area and ui_button can be the same element
		*/
		
		ui_button: null,
		ui_list: null,
		ui_drop_area: null,
		
		// Settings
		
		url: 'upload.php',
		accept: '*/*',
		method: null, // for debugging, values: 'HTML5', 'HTML4', 'Flash' or null for automatic selection
		multiple: true,
		autostart: true,
		max_queue: 5,
		min_file_size: 1,
		max_file_size: 0,
		block_size: 2008000, // Juan doesn't recommend less than 101400 and more than 502000
		vars: { // additional to be sent to backend
			seao_fancy_uploader : true
		},
		gravity_center: null, // an element after which hidden SeaoFancyUploader elements are output
		// Events
		
		/*
		onReset: function (method) {},
		
		onSelectError: function (error, filename, filesize) {},
		
		onAddFiles: function () {},
		
		onUploadStart: function (){}, // start of queue
		onUploadComplete: function (num_uploaded){}, // end of queue
		onUploadProgress: function (perc) {}, // on progress of queue
		
		onItemAdded: function (element, file, imageData) {}, // listener should add HTML for the item (get params like file.name, file.size), imageData is sent only for images
		onItemCancel: function (element, file) {},
		onItemComplete: function (item, file, response) {},
		onItemError: function (item, response, id) {},
		onItemProgress: function (item, perc) {}
		*/
	
	},
	
	// Vars
	
	method: null,
	flashObj: null,
	flashloaded: false,
	uiButton: null,
	uiList: null,
	uiDropArea: null,
	hiddenContainer: null,
		

	
	// Init

	initialize: function (options) {
	
		/*
		* Check what's available
		* and initiate based on that
		* note: swap bits here to make Flash preferred to HTML5
		*/
		
		this.method = options.method; // ONLY FOR DEBUGGING!
		
		// Check HTML5 support & if module is available
		
		if (!this.method && window.File && window.FileList && window.Blob && typeof SeaoFancyUploader['HTML5'] != 'undefined') { //  && window.FileReader
			
			this.method = 'HTML5';

			// Unfortunally Opera 11.11 has an incomplete Blob support
			if (Browser.opera && Browser.version <= 11.11) this.method = null;
			
		}

		// Check flash support & if module is available
		if (!this.method && typeof SeaoFancyUploader['Flash'] != 'undefined') this.method = Browser.Plugins.Flash && Browser.Plugins.Flash.version >= 9 ? 'Flash' : null;
		
		// If not Flash or HTML5, go for HTML4 if module is available
		if (!this.method && typeof SeaoFancyUploader['HTML4'] != 'undefined') this.method = 'HTML4';
				
		// Activate proper method (self-extend)
		if(typeof SeaoFancyUploader[this.method] != 'undefined') {
			
			return new SeaoFancyUploader[this.method](options);
			
		}
		
	},
	
	activate: function(){
		
		// set UI elements
		
		this.uiButton = $(this.options.ui_button);
		this.uiList = $(this.options.ui_list);
		this.uiDropArea = $(this.options.ui_drop_area);
		this.remoteInput = $(this.options.remote_input);
		this.remoteButton = $(this.options.remote_button);
		
		// just any of elements, to keep injected invisible elements next to
		this.gravityCenter = this.options.gravity_center;
		if(!this.gravityCenter) this.gravityCenter = this.uiButton || this.uiList || this.uiDropArea;
		if(!this.gravityCenter) return;
		
		// container for invisible things
		this.hiddenContainer = new Element('div', {'class': 'seao-fancy-uploader-hidden-wrap'}).inject(this.gravityCenter, 'after');
		
		this._decodeVars();

		// setup things fresh
		this.reset();

		this.fireEvent('onActivate');
	},
	
	
	
	
	/* Public methods */
	
	// adds files before upload
	
	addFiles: function (files) {
		
		for (var i = 0, f; f = files[i]; i++) {
		
			var fname = f.name || f.fileName;
			var fsize = f.size || f.fileSize;
			
			if (fsize != undefined) {

				if (fsize < this.options.min_file_size) {
					this.fireEvent('onSelectError', ['minfilesize', fname, fsize]);
					return false;
				}

				if (this.options.max_file_size > 0 && fsize > this.options.max_file_size) {
					this.fireEvent('onSelectError', ['maxfilesize', fname, fsize]);
					return false;
				}

				if (this._uploadedMaxFiles()) {
					this.fireEvent('onSelectError', ['limitFilesExceeded']);
					break;
				}

				if (!this._isValidFile(f)) {
					this.fireEvent('onSelectError', ['fileTypeInvalid', fname, fsize]);
					continue;
				}
			}
			
			var id = this.fileList.length;
			
			this.fileList[id] = {
				file: f,
				id: id,
				uniqueid: String.uniqueID(),
				checked: true,
				name: fname,
				type: f.type || f.extension || this._getFileExtension(fname) ,
				size: fsize,
				is_url: 0,
				uploaded: false,
				uploading: false,
				progress: 0,
				error: false
			};
			
			if (this.uiList) this._addNewItem(this.fileList[this.fileList.length - 1]);

		}
		
		// fire!
		this.fireEvent('onAddFiles', [this.fileList.length]);

		if (this.options.autostart) this.upload();

	},
	
	// starts upload
	
	upload: function () {
		
		if(!this.isUploading){
		
			this.isUploading = true;
			this.fireEvent('onUploadStart');
			
			this._updateQueueProgress();
			
		}

	},
	
	// cancels a specified item
	
	cancel: function(id, item) {
		
		if(this.fileList[id]){
			
			this.fileList[id].checked = false;
			this.fileList[id].cancelled = true;
			
			if(this.fileList[id].error) {
				this.nErrors--;
			} else if(this.fileList[id].uploading) {
				this.nCurrentUploads--;
			}
		
		}
		
		this.nCancelled++;
		
		if(this.nCurrentUploads <= 0) this._queueComplete();
		
		this.fireEvent('onItemCancel', [item]);
		
	},
	
	// kill at will
	
	kill: function(){
		
		// cancel all
		
		this.fileList.each(function(f, i){
			
			this.cancel(f.id);
						
		}, this);
		
	},
	
	
	
	
	
	
	
	/* Private methods */
		
	// Activate button used by HTML4 & HTML5 uploads
	
	_activateHTMLButton: function (){
	
		if(!this.uiButton) return;
		
		this.uiButton.addEvent('click', function (e) {
			e.stop();
			
			// Click trigger for input[type=file] only works in FF 4.x, IE and Chrome
			if(this.options.multiple || (!this.options.multiple && !this.isUploading)) this.lastInput.click();

		}.bind(this));
		
	},
	
	// creates hidden input elements to handle file uploads nicely
	
	_newInput: function (formcontainer) {
				
		if(!formcontainer) formcontainer = this.hiddenContainer;
		
		// Input File
		this.lastInput = new Element('input', {
			id: 'tbxFile_' + this._countInputs(),
			name: 'tbxFile_' + this._countInputs(),
			type: 'file',
			size: 1,
			styles: {
				position: 'absolute',
				top: 0,
				left: 0/*,
				border: 0*/
			},
			multiple: this.options.multiple,
			accept: this.options.accept

		}).inject(formcontainer);


		// Old version of firefox and opera don't support click trigger for input files fields
		// Internet "Exploiter" do not allow trigger a form submit if the input file field was not clicked directly by the user
		if (this.method != 'Flash' && (Browser.firefox2 || Browser.firefox3 || Browser.opera || Browser.ie)) {
			this._positionInput();
		} else {
			this.lastInput.setStyle('visibility', 'hidden');
		}
		
	},

	_positionInput: function () {
		
		if(!this.uiButton && true) return;
		
		// Get addFile attributes
		var btn = this.uiButton,
			btncoords = btn.getCoordinates(btn.getOffsetParent());

		/*
		this.lastInput.position({
		  relativeTo: document.id(subcontainer_id+'_btnAddfile'),
		  position: 'bottomLeft'
		});
		*/

		this.lastInput.setStyles({
			top: btncoords.top,
			left: btncoords.left - 1,
			width: btncoords.width + 2,
			// Extra space for cover button border
			height: btncoords.height,
			opacity: 0.0001,
			// Opera opacity ninja trick
			'-moz-opacity': 0
		});

	},

	_updateQueueProgress: function (){
		
		var perc = 0,
			n_checked = 0;
		
		this.fileList.each(function(f){
			if (f.checked) {
				perc += f.progress;
				n_checked++;
			}
		});
		
		if(n_checked == 0) return;
		
		this.queuePercent = perc / n_checked;
		
		this.fireEvent('onUploadProgress', [this.queuePercent, this.nUploaded + this.nCurrentUploads, this.fileList.length-this.nCancelled]);
		
	},
	
	_queueComplete: function(){
		
		this.isUploading = false;
		
		this.fireEvent('uploadComplete', [this.nUploaded, this.nErrors]);
		
		if(this.nErrors==0) this.reset();
		
	},
	
	
	_itemProgress: function(item, perc){
		
		this.fireEvent('itemProgress', [item, perc]);
		
		this._updateQueueProgress();
		
	},

	_itemComplete: function(item, file, response){
		
		if(file.cancelled) return;
		
		this.nCurrentUploads--;
		this.nUploaded++;
				
		this.fileList[file.id].uploaded = true;
		this.fileList[file.id].progress = 100;
		
		this._updateQueueProgress();
		
		this.fireEvent('onItemComplete', [item, file, response]);
		
		if(this.nCurrentUploads <= 0 && this.nUploaded + this.nErrors + this.nCancelled == this.fileList.length) this._queueComplete();
		
	},

	_itemError: function(item, file, response){
		
		this.nCurrentUploads--;
		this.nErrors++;
		
		if(typeof file.id != 'undefined' && typeof this.fileList[file.id] != 'undefined'){
			this.fileList[file.id].uploaded = true;
			this.fileList[file.id].error = true;
		}
		
		this.fireEvent('onItemError', [item, file, response]);
		
		if(this.nCurrentUploads <= 0) this._queueComplete();
		
	},	
	
	_addNewItem: function (file) {
		
		// create a basic wrapper for the thumb
		
		var item = new Element('li', {
			'class': 'seao-fancy-uploader-item',
			'id': 'seao-fancy-uploader-item-' + file.uniqueid + '-' + file.id
		}).inject(this.uiList);
		file.element = item;

		// check file type, and get thumb if it's an image
		
		// Get the URL object (unavailable in Safari 5-)
		window.URL = window.URL || window.webkitURL;
		
		if (file.type.match('image') && window.URL) { //typeof FileReader !== 'undefined' && 
						
			// measure size of the blob image
			var img = new Element('img', {'style': 'visibility: hidden; position: absolute;'});
			img.addEvent('load', function(e) {
				this.fireEvent('itemAdded', [item, file, img.src, img.getSize()]); // e.target.result for large images crashes Chrome?
				window.URL.revokeObjectURL(img.src); // Clean up after yourself.
				img.destroy();
			}.bind(this));
			
			// if image is corrupted
			img.addEvent('error', function(e) {
				this.fireEvent('itemAdded', [item, file]); // e.target.result for large images crashes Chrome?
				window.URL.revokeObjectURL(img.src); // Clean up after yourself.
				img.destroy();
			}.bind(this));
			
			img.src = window.URL.createObjectURL(file.file);
			this.gravityCenter.adopt(img);
			
		} else {
			
			this.fireEvent('itemAdded', [item, file]);
			
		}
		
	},

	_getInputs: function () {
		return this.hiddenContainer.getElements('input[type=file]');
	},

	_getForms: function () {
		return this.hiddenContainer.getElements('form');
	},

	_countInputs: function () {
		var containers = this._getInputs();
		return containers.length;
	},
	
	_getFileExtension: function(filename){
		return filename.split('.').pop();
	},
	
	reset: function(){
		
		// Add vars to URL (query string)
		this.url = this.options.url + ((!this.options.url.match('\\?')) ? '?' : '&') + Object.toQueryString(this.options.vars)
		
		this.fileList = new Array();
		this.lastInput = undefined; // stores new, currently unused hidden input field
		this.nCurrentUploads = 0;
		this.nUploaded = 0;
		this.nErrors = 0;
		this.nCancelled = 0;
		this.queuePercent = 0;
		this.isUploading = false;
		
		if(this.hiddenContainer) this.hiddenContainer.empty();
		
		this._newInput();
		
		this.fireEvent('reset', [this.method]);
		
	},
	
	// Change handling response to what you use in backend here..
	
	_checkResponse: function(response){
		return (response.error == 0);
	},

	// check if max files limit has reached
	_uploadedMaxFiles: function() {
		return this.limitFiles ? this.limitFiles <= this.uiList.getElements('.seao-fancy-uploader-item').length : false;
	},

	// convert bytes into MB or KB as per the need
	_convertSize: function(bytes) {
		return (kb = bytes / 1024) < 1024 ? Math.round(kb) + ' KB' : Math.round(kb / 1024) + ' MB';
	},

	// check if file has valid type / extension
	_isValidFile: function(file) {

		// If there are no accepted mime types, it's OK
		acceptedFiles = this.options.accept.split(",");

		var mimeType = file.type;
		var baseMimeType = mimeType.replace(/\/.*$/, "");

		for (var _iterator = acceptedFiles, _isArray = true, _i = 0, _iterator = _isArray ? _iterator : _iterator[Symbol.iterator]();;) {
			var _ref;

			if (_isArray) {
				if (_i >= _iterator.length) break;
				_ref = _iterator[_i++];
			} else {
				_i = _iterator.next();
				if (_i.done) break;
				_ref = _i.value;
			}

			var validType = _ref;

			validType = validType.trim();
			if (validType.charAt(0) === ".") {
				if (file.name.toLowerCase().indexOf(validType.toLowerCase(), file.name.length - validType.length) !== -1) {
					return true;
				}
			} else if (/\/\*$/.test(validType)) {
				// This is something like a image/* mime type
				if (baseMimeType === validType.replace(/\/.*$/, "")) {
					return true;
				}
			} else {
				if (mimeType === validType) {
					return true;
				}
			}
		}
		return false;
	},

	// decode extra vars according to its type
	_decodeVars: function() {
		if (typeOf(this.options.vars) == 'object') return true;
		if (typeOf(this.options.vars) == 'array') {
			this.options.vars = {seao_fancy_uploader : true }
			return false;
		}
		try {
			this.options.vars = JSON.decode(this.options.vars);
			this.options.vars.seao_fancy_uploader = true;
		} catch(err) {
			console.log('Unable To Decode Vars: Message - ' + err.message);
			this.options.vars = {seao_fancy_uploader : true }
			return false;
		}
		return true;
	},

	// get error message
	getErrorMessage: function(type, filename, filesize) {
		errorMessage = this.errorMessageArray[type] || type;
		return this.language.translate(errorMessage, filename, this._convertSize(filesize)) ;
	},

	setErrorMessages: function() {
		if (!this.errorMessageArray)
			this.errorMessageArray = [];
		this.setErrorMessage('minfilesize', 'Minimum Files Size Deceeded - %s ( %s )');
		this.setErrorMessage('maxfilesize', 'Maximum Files Size Exceeded - %s ( %s )');
		this.setErrorMessage('limitFilesExceeded', 'Reached Maximum File Uploads.');
		this.setErrorMessage('fileTypeInvalid', 'Invalid File Type - %s (%s)');
		this.setErrorMessage('remoteUrlInvalid', 'Invalid URL');
		return this.errorMessageArray;
	},
	setErrorMessage: function(type, message) {
		this.errorMessageArray[type] = message;
	},
	_log: function(message) {
		if (this.debug && console && 'log' in console) {
			console.log('SeaoFancyUploader: ' + message);
		}
	},
	_error: function(message) {
		if (this.debug && console && 'error' in console) {
			console.error('SeaoFancyUploader: ' + message);
		}
	},
	_warn: function(message) {
		if (this.debug && console && 'warn' in console) {
			console.warn('SeaoFancyUploader: ' + message);
		}
	},

	// populate uploaded files
	_populateItems: function(fileArray = null) {
		fileArray = fileArray || this.options.populateFiles;
		if (typeOf(fileArray) !== 'array' || fileArray.length === 0) return false;
		var self = this;
		fileArray.each(function(file) {
			var item = new Element('li', {
				'class': 'seao-fancy-uploader-item',
			}).inject(self.uiList);
			self.fireEvent('onItemPopulated', [item, file]);
		});
		this.fireEvent('onPopulateFiles', [fileArray.length]);
		this.updateUiList();
	},

	// Activate trigger for remote files button
	_activateRemoteButton: function () {
		if (this.remoteButton && this.remoteInput) {
			var self = this;
			this.remoteButton.addEvent('click', function (e) {
				e.stop();
				self.addRemoteFile(self.remoteInput.get('value'));
			}.bind(this));
		}
	},

	// check url for remote files
	_isValidRemoteUrl: function(url) {
		if (!url) return false;
		try {
			new URL(url);
		} catch (err) {
			return false;
		}

		return true;
	},

	// add remote file
	addRemoteFile: function(url) {
		if (!this._isValidRemoteUrl(url)) {
			this.fireEvent('remoteFetchError', ['remoteUrlInvalid', url]);
			return false;
		}

		if (this._uploadedMaxFiles()) {
			this.fireEvent('onSelectError', ['limitFilesExceeded']);
			return false;
		}

		if (!this.options.accept.match('image')) {
			this.fireEvent('remoteFetchStart', [url]);
			this._addNewItemRemote(url, this.options.fileType);
			this.fireEvent('remoteFetchComplete', [url]);
			return true;
		}

		var el = new Element('img', {'style': 'display: none; visibility: hidden; position: absolute;'});
		el.addEvent('load', function(e) {
			this._addNewItemRemote(url, 'image');
			this.remoteInput.set('value', '');
			el.destroy();
			this.fireEvent('remoteFetchComplete', [url]);
		}.bind(this));

		el.addEvent('error', function(e) {
			el.destroy();
			this.fireEvent('remoteFetchError', ['remoteUrlInvalid', url]);
		}.bind(this));

		el.src = url;
		this.gravityCenter.adopt(el);
		this.fireEvent('remoteFetchStart', [url]);
	},

	// add remote file
	_addNewItemRemote: function(url, type) {
		var id = this.fileList.length;
		fname = url.split('/').pop().split('?')[0];
		fsize = 0;

		this.fileList[id] = {
			file: url,
			id: id,
			uniqueid: String.uniqueID(),
			checked: true,
			name: fname,
			type: type,
			size: fsize,
			is_url: 1,
			uploaded: false,
			uploading: false,
			progress: 0,
			error: false
		};

		file = this.fileList[id];
		var item = new Element('li', {
			'class': 'seao-fancy-uploader-item',
			'id': 'seao-fancy-uploader-item-' + file.uniqueid + '-' + file.id
		}).inject(this.uiList);
		file.element = item;

		this.fireEvent('itemAdded', [item, file, url]);

		this.fireEvent('onAddFiles', [this.fileList.length]);

		this.upload();

		return true;
	},

	// get individual li width for individual progressbar animation
	getItemWidth: function() {
		if (this.itemWidth) return this.itemWidth;
		this.itemWidth = this.uiList.getElement('.seao-fancy-uploader-item').getSize().x;
		return this.itemWidth;
	},

	// get individual li height for individual progressbar animation
	getItemHeight: function() {
		if (this.itemHeight) return this.itemHeight;
		this.itemHeight = this.uiList.getElement('.seao-fancy-uploader-item').getSize().y;
		return this.itemHeight;
	},

	// get total width for progressbar animation
	getTotalWidth: function() {
		if (this.totalWidth) return this.totalWidth;
		this.totalWidth = this.progressBar.getParent().getSize().x;
		return this.totalWidth;
	},

	// update ui list
	updateUiList: function() {
		this.fireEvent('onUpdateUiList');
	},
});