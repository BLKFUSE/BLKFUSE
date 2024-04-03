<link href="application/modules/Sescontest/externals/styles/video-js.min.css" rel="stylesheet">
<link href="application/modules/Sescontest/externals/styles/videojs.record.css" rel="stylesheet">
<link href="application/modules/Sescontest/externals/styles/style_webvideo.css" rel="stylesheet">
<?php if($_GET['media_type'] == 'video' || $_GET['media_type'] == 'audio'):?>
  <script src="application/modules/Sescontest/externals/scripts/video.min.js"></script>
  <script src="application/modules/Sescontest/externals/scripts/RecordRTC.js"></script>
  <script src="application/modules/Sescontest/externals/scripts/DetectRTC.js"> </script>
  <script src="application/modules/Sescontest/externals/scripts/wavesurfer.min.js"></script>
  <script src="application/modules/Sescontest/externals/scripts/wavesurfer.microphone.min.js"></script>
  <script src="application/modules/Sescontest/externals/scripts/videojs.wavesurfer.min.js"></script>
  <script src="application/modules/Sescontest/externals/scripts/videojs.record.js"></script>
<?php elseif($_GET['media_type'] == 'image'):?>
  <script src="application/modules/Sescontest/externals/scripts/photo.min.js"></script>
  <script src="application/modules/Sescontest/externals/scripts/photojs.record.min.js"></script>
<?php endif;?>
  
  <style>
  /* change player background color */
  #myAudio {
      background-color: #f00;
  }
  </style>
  
<?php if($_GET['media_type'] == 'audio'):?>
<audio id="myAudio" class="video-js vjs-default-skin"></audio>

<script>
var player = videojs("myAudio",
{
    controls: true,
    width: 600,
    height: 300,
    plugins: {
        wavesurfer: {
            src: "live",
            waveColor: "black",
            progressColor: "#2E732D",
            debug: true,
            cursorWidth: 1,
            msDisplayMax: 20,
            hideScrollbar: true
        },
        record: {
            audio: true,
            video: false,
            maxLength: 20,
            debug: true
        }
    }
});
// error handling
player.on('deviceError', function()
{
    console.log('device error:', player.deviceErrorCode);
});

// user clicked the record button and started recording
player.on('startRecord', function()
{
    console.log('started recording!');
});

// user completed recording and stream is available
player.on('finishRecord', function()
{
    // the blob object contains the recorded data that
    // can be downloaded by the user, stored on server etc.
    if(parent.document.getElementById('sescontest_audio_file'))
    parent.document.getElementById('sescontest_audio_file').value = '';
    parent.recordedDataContest = player.recordedData;
    player.recorder.stopDevice(); 
    parent.removeImage();
    parent.removeLinkImage();
});
</script>
<?php elseif($_GET['media_type'] == 'video'):?>
        <section class="experiment recordrtc" style="height:450px;">
            <h3 class="error-message" style="display:none;">This feature is currently not available for your device.</h3>
            <h2 class="header">
                <select class="recording-media" style="display: none;">
                    <option value="record-video">Video</option>
                </select>
                <select class="media-container-format" style="display: none;">
                    <option>WebM</option>
                </select>
                <button style="background:none;padding:8px 15px;border-width:2px;">Start Recording</button>
            </h2>
            <video controls playsinline autoplay muted=false volume=1 style="height:100%;width:100%;"></video>
        </section>
          <script>
            (function() {
                var params = {},
                    r = /([^&=]+)=?([^&]*)/g;
                function d(s) {
                    return decodeURIComponent(s.replace(/\+/g, ' '));
                }

                var match, search = window.location.search;
                while (match = r.exec(search.substring(1))) {
                    params[d(match[1])] = d(match[2]);

                    if(d(match[2]) === 'true' || d(match[2]) === 'false') {
                        params[d(match[1])] = d(match[2]) === 'true' ? true : false;
                    }
                }
                window.params = params;
            })();
        </script>
        <script>
            var recordingDIV = document.querySelector('.recordrtc');
            var recordingMedia = recordingDIV.querySelector('.recording-media');
            var recordingPlayer = recordingDIV.querySelector('video');
            var mediaContainerFormat = recordingDIV.querySelector('.media-container-format');
            parent.RecordRTC = RecordRTC;
            //navigator.userAgent.match(/Mac OS X|iPhone|iPad|iPod/i)
            if(true){
                recordingDIV.querySelector('button').onclick = function() {
                var button = this;
                console.log(button.innerHTML);
                if(button.innerHTML === 'Stop Recording') {
                    button.disabled = true;
                    button.disableStateWaiting = true;
                    setTimeout(function() {
                        button.disabled = false;
                        button.disableStateWaiting = false;
                    }, 2 * 1000);
                    button.innerHTML = 'Start Recording';
                    function stopStream() {
                        if(button.stream && button.stream.stop) {
                            button.stream.stop();
                            button.stream = null;
                        }
                    }
                    if(button.recordRTC) {
                        if(button.recordRTC.length) {
                            button.recordRTC[0].stopRecording(function(url) {
                                if(!button.recordRTC[1]) {
                                    button.recordingEndedCallback(url);
                                    stopStream();
                                    parent.recordedDataContest = button.recordRTC[0];
                                    saveToDiskOrOpenNewTab(button.recordRTC[0]);
                                    return;
                                }
                                button.recordRTC[1].stopRecording(function(url) {
                                    button.recordingEndedCallback(url);
                                    stopStream();
                                });
                            });
                        }
                        else {
                            button.recordRTC.stopRecording(function(url) {
                                button.recordingEndedCallback(url);
                                parent.recordedDataContest = button.recordRTC.blob;
                                saveToDiskOrOpenNewTab(button.recordRTC.blob);
                                recordingPlayer.pause();
                                button.innerHTML = 'Start Recording';
                                stopStream();
                            });
                        }
                    }
                    return;
                }
                button.disabled = true;
                var commonConfig = {
                    onMediaCaptured: function(stream) {
                        button.stream = stream;
                        if(button.mediaCapturedCallback) {
                            button.mediaCapturedCallback();
                        }
                        
                        button.innerHTML = 'Stop Recording';
                        button.disabled = false;
                    },
                    onMediaStopped: function() {
                        button.innerHTML = 'Start Recording';

                        if(!button.disableStateWaiting) {
                            button.disabled = false;
                        }
                    },
                    onMediaCapturingFailed: function(error) {
                        if(error.name === 'PermissionDeniedError' && !!navigator.mozGetUserMedia) {
                            InstallTrigger.install({
                                'Foo': {
                                    // https://addons.mozilla.org/firefox/downloads/latest/655146/addon-655146-latest.xpi?src=dp-btn-primary
                                    URL: 'https://addons.mozilla.org/en-US/firefox/addon/enable-screen-capturing/',
                                    toString: function () {
                                        return this.URL;
                                    }
                                }
                            });
                        }
                        commonConfig.onMediaStopped();
                    }
                };
                
                captureVideo(commonConfig);
                recordingPlayer.onpause = function(){
                    if(typeof button.recordRTC == 'undefined' || typeof button.recordRTC.blob == 'undefined')
                        return false;
                    saveToDiskOrOpenNewTab(button.recordRTC.blob);
                }
                button.mediaCapturedCallback = function() {
                    button.recordRTC = RecordRTC(button.stream, {
                        type: 'video',
                        disableLogs: params.disableLogs || false,
                        canvas: {
                            width: params.canvas_width || 320,
                            height: params.canvas_height || 240
                        },
                        frameInterval: typeof params.frameInterval !== 'undefined' ? parseInt(params.frameInterval) : 20 // minimum time between pushing frames to Whammy (in milliseconds)
                    });
                    button.recordingEndedCallback = function(url) {
                        recordingPlayer.src = null;
                        recordingPlayer.srcObject = null;

                        if(mediaContainerFormat.value === 'Gif') {
                            recordingPlayer.pause();
                            recordingPlayer.poster = url;

                            recordingPlayer.onended = function() {
                                recordingPlayer.pause();
                                recordingPlayer.poster = URL.createObjectURL(button.recordRTC.blob);
                            };
                            return;
                        }
                        recordingPlayer.src = url;
                        recordingPlayer.onended = function() {
                            recordingPlayer.pause();
                            recordingPlayer.src = URL.createObjectURL(button.recordRTC.blob);
                        };
                    };
                    button.recordRTC.startRecording();
                };
                if(recordingMedia.value === 'record-audio-plus-video') {
                      captureAudioPlusVideo(commonConfig);
                      button.mediaCapturedCallback = function() {
                        if(DetectRTC.browser.name !== 'Firefox') { // opera or chrome etc.
                            button.recordRTC = [];

                            if(!params.bufferSize) {
                                // it fixes audio issues whilst recording 720p
                                params.bufferSize = 16384;
                            }
                            var audioRecorder = RecordRTC(button.stream, {
                                type: 'audio',
                                bufferSize: typeof params.bufferSize == 'undefined' ? 0 : parseInt(params.bufferSize),
                                sampleRate: typeof params.sampleRate == 'undefined' ? 44100 : parseInt(params.sampleRate),
                                leftChannel: params.leftChannel || false,
                                disableLogs: params.disableLogs || false,
                                recorderType: DetectRTC.browser.name === 'Edge' ? StereoAudioRecorder : null
                            });
                            var videoRecorder = RecordRTC(button.stream, {
                                type: 'video',
                                disableLogs: params.disableLogs || false,
                                canvas: {
                                    width: params.canvas_width || 320,
                                    height: params.canvas_height || 240
                                },
                                frameInterval: typeof params.frameInterval !== 'undefined' ? parseInt(params.frameInterval) : 20 // minimum time between pushing frames to Whammy (in milliseconds)
                            });

                            // to sync audio/video playbacks in browser!
                            videoRecorder.initRecorder(function() {
                                audioRecorder.initRecorder(function() {
                                    audioRecorder.startRecording();
                                    videoRecorder.startRecording();
                                });
                            });

                            button.recordRTC.push(audioRecorder, videoRecorder);

                            button.recordingEndedCallback = function() {
                                var audio = new Audio();
                                audio.src = audioRecorder.toURL();
                                audio.controls = true;
                                audio.autoplay = true;

                                audio.onloadedmetadata = function() {
                                    recordingPlayer.src = videoRecorder.toURL();
                                };

                                recordingPlayer.parentNode.appendChild(document.createElement('hr'));
                                recordingPlayer.parentNode.appendChild(audio);

                                if(audio.paused) audio.play();
                            };
                            return;
                        }

                        button.recordRTC = RecordRTC(button.stream, {
                            type: 'video',
                            disableLogs: params.disableLogs || false,
                            // we can't pass bitrates or framerates here
                            // Firefox MediaRecorder API lakes these features
                        });
                        button.recordingEndedCallback = function(url) {
                            recordingPlayer.srcObject = null;
                            recordingPlayer.muted = false;
                            recordingPlayer.src = url;
                            recordingPlayer.onended = function() {
                                recordingPlayer.pause();
                                recordingPlayer.src = URL.createObjectURL(button.recordRTC.blob);
                            };
                        };
                        button.recordRTC.startRecording();
                    };
                }
                }
            } else {
                recordingDIV.querySelector('.header').style.display = "none";
                recordingDIV.querySelector('video').style.display = "none";
                recordingDIV.querySelector('.error-message').style.display = "block";
            }
            function captureVideo(config) {
                captureUserMedia({video: true}, function(videoStream) {
                    recordingPlayer.srcObject = videoStream;

                    config.onMediaCaptured(videoStream);

                    videoStream.onended = function() {
                        config.onMediaStopped();
                    };
                }, function(error) {
                    config.onMediaCapturingFailed(error);
                });
            }
            function captureAudio(config) {
                captureUserMedia({audio: true}, function(audioStream) {
                    recordingPlayer.srcObject = audioStream;

                    config.onMediaCaptured(audioStream);

                    audioStream.onended = function() {
                        config.onMediaStopped();
                    };
                }, function(error) {
                    config.onMediaCapturingFailed(error);
                });
            }

            function captureAudioPlusVideo(config) {
                captureUserMedia({video: true, audio: true}, function(audioVideoStream) {
                    recordingPlayer.srcObject = audioVideoStream;

                    config.onMediaCaptured(audioVideoStream);

                    audioVideoStream.onended = function() {
                        config.onMediaStopped();
                    };
                }, function(error) {
                    config.onMediaCapturingFailed(error);
                });
            }
            function captureUserMedia(mediaConstraints, successCallback, errorCallback) {
                navigator.mediaDevices.getUserMedia(mediaConstraints).then(successCallback).catch(errorCallback);
            }

            function setMediaContainerFormat(arrayOfOptionsSupported) {
                var options = Array.prototype.slice.call(
                    mediaContainerFormat.querySelectorAll('option')
                );

                var selectedItem;
                options.forEach(function(option) {
                    option.disabled = true;

                    if(arrayOfOptionsSupported.indexOf(option.value) !== -1) {
                        option.disabled = false;

                        if(!selectedItem) {
                            option.selected = true;
                            selectedItem = option;
                        }
                    }
                });
            }

            recordingMedia.onchange = function() {
                setMediaContainerFormat(['WebM', /*'Mp4',*/ 'Gif']);
            };
            if(DetectRTC.browser.name === 'Edge') {
                // webp isn't supported in Microsoft Edge
                // neither MediaRecorder API
                // so lets disable both video/screen recording options

                console.warn('Neither MediaRecorder API nor webp is supported in Microsoft Edge. You cam merely record audio.');
                setMediaContainerFormat(['WAV']);
            }

            if(DetectRTC.browser.name === 'Firefox') {
                // Firefox implemented both MediaRecorder API as well as WebAudio API
                // Their MediaRecorder implementation supports both audio/video recording in single container format
                // Remember, we can't currently pass bit-rates or frame-rates values over MediaRecorder API (their implementation lakes these features)

                recordingMedia.innerHTML = '<option value="record-audio-plus-video">Audio+Video</option>'
                                            + '<option value="record-audio-plus-screen">Audio+Screen</option>'
                                            + recordingMedia.innerHTML;
            }

            // disabling this option because currently this demo
            // doesn't supports publishing two blobs.
            // todo: add support of uploading both WAV/WebM to server.
            if(false && DetectRTC.browser.name === 'Chrome') {
                recordingMedia.innerHTML = '<option value="record-audio-plus-video">Audio+Video</option>'
                                            + recordingMedia.innerHTML;
                console.info('This RecordRTC demo merely tries to playback recorded audio/video sync inside the browser. It still generates two separate files (WAV/WebM).');
            }
            function saveToDiskOrOpenNewTab(recordRTC) {
                if(parent.document.getElementById('sescontest_video_file'))
                  parent.document.getElementById('sescontest_video_file').value = '';
                  parent.recordedDataContest = recordRTC;
                  parent.document.getElementById('sescontest_video_file').value = '';
                  parent.resetLinkData();
            }
        </script>
 <?php else:?>
  <video id="myImage" class="video-js vjs-default-skin"></video>
<script>

var options = {
    controls: true,
    fluid: false,
    bigPlayButton: false,
    controlBar: {
        volumePanel: false,
        fullscreenToggle: false
    },
    // dimensions of the video.js player
    width: 640,
    height: 480,
    plugins: {
        record: {
            debug: true,
            imageOutputType: 'dataURL',
            imageOutputFormat: 'image/png',
            imageOutputQuality: 0.92,
            image: {
              // image media constraints: set resolution of camera
              width: { min: 640, ideal: 640, max: 1280 },
              height: { min: 480, ideal: 480, max: 920 }
            },
            // dimensions of captured video frames
            frameWidth: 640,
            frameHeight: 480
        }
    }
};
var player = videojs('myImage', options, function() {
//     // print version information at startup
//     var msg = 'Using video.js ' + videojs.VERSION +
//         ' with videojs-record ' + videojs.getPluginVersion('record');
//     videojs.log(msg);
});

// var player = videojs("myImage",
// {
//     controls: true,
//     width: 320,
//     height: 240,
//     controlBar: {
//         volumeMenuButton: false,
//         fullscreenToggle: false
//     },
//     plugins: {
//         record: {
//             image: true,
//             debug: true
//         }
//     }
// });
// error handling
player.on('deviceError', function()
{
    console.warn('device error:', player.deviceErrorCode);
});
player.on('error', function(error)
{
    console.log('error:', error);
});
// snapshot is available
player.on('finishRecord', function(e)
{
    // the blob object contains the image data that
    // can be downloaded by the user, stored on server etc.
    parent.recordedDataContest = player.recordedData;
//     player.recorder.stopDevice(); 
    parent.removeImage();
    parent.removeLinkImage();
    parent.removeFromurlImage();
});
</script>
<?php endif; ?>


