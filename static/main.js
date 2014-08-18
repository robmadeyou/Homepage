var FFTSIZE = 1024;      // number of samples for the analyser node FFT, min 32
var TICK_FREQ = 30;     // how often to run the tick function, in milliseconds
var assetsPath = "music/"; // Create a single item to load.
var src = "";  // set up our source
var soundInstance;      // the sound instance we create
var analyserNode;       // the analyser node that allows us to visualize the audio
var freqFloatData, freqByteData, timeByteData;  // arrays to retrieve data from analyserNode
var canvas = document.getElementById( "barCanvas" );
var ctx = canvas.getContext( "2d" );
canvas.style.width = "100%";
canvas.style.height = "100%";
canvas.width  = canvas.offsetWidth;
canvas.height = canvas.offsetHeight;

function init( song ) {

	if( song != "" )
	{
		if( assetsPath + src == assetsPath + song)
		{
			handleLoad(this);
			return;
		}
		else
		{
			src = song;
		}
	}
	if (window.top != window) {
		document.getElementById("header").style.display = "none";
	}

    $( '#songTitle' ).html( src.replace( ".m4a", "" ) );

	// Web Audio only demo, so we register just the WebAudioPlugin and if that fails, display fail message
	if (!createjs.Sound.registerPlugins([createjs.WebAudioPlugin])) {
		document.getElementById("error").style.display = "block";
		//return;
	}

	// create a new stage and point it at our canvas:
	createjs.Sound.addEventListener("fileload", createjs.proxy(handleLoad,this)); // add an event listener for when load is completed
	createjs.Sound.registerSound(assetsPath + src);  // register sound, which preloads by default

}

function handleLoad(evt) {
	var context = createjs.Sound.activePlugin.context;
	analyserNode = context.createAnalyser();
	analyserNode.fftSize = FFTSIZE;  //The size of the FFT used for frequency-domain analysis. This must be a power of two
	analyserNode.smoothingTimeConstant = 0.85;  //A value from 0 -> 1 where 0 represents no time averaging with the last analysis frame
	analyserNode.connect(context.destination);  // connect to the context.destination, which outputs the audio
	var dynamicsNode = createjs.Sound.activePlugin.dynamicsCompressorNode;
	dynamicsNode.disconnect();  // disconnect from destination
	dynamicsNode.connect(analyserNode);
	freqFloatData = new Float32Array(analyserNode.frequencyBinCount);
	freqByteData = new Uint8Array(analyserNode.frequencyBinCount);
	timeByteData = new Uint8Array(analyserNode.frequencyBinCount);
	startPlayback(evt);
}

function startPlayback(evt) {
	soundInstance = createjs.Sound.play(assetsPath + src, {loop:0});
	soundInstance.addEventListener( "complete" , createjs.proxy(ajaxGetSong, this));

	// start the tick and point it at the window so we can do some work before updating the stage:
	createjs.Ticker.addEventListener("tick", tick);
	createjs.Ticker.setInterval(TICK_FREQ);
}

function next( song )
{
	if( src != "" )
	{
		createjs.Ticker.removeEventListener( "tick", tick );
		createjs.Sound.stop( );
		createjs.Sound.removeAllEventListeners( );
		createjs.Sound.activePlugin.dynamicsCompressorNode.disconnect( );
	}
	init( song );
}

function tick(evt) {
	analyserNode.getFloatFrequencyData(freqFloatData);  // this gives us the dBs
	analyserNode.getByteFrequencyData(freqByteData);  // this gives us the frequency
	analyserNode.getByteTimeDomainData(timeByteData);  // this gives us the waveform
	//ctx.clearRect(0,0,canvas.width,canvas.height);
	canvas.width = canvas.width;
	ctx.fillStyle = "#151515";
	var width = Math.ceil(canvas.width / freqByteData.length)
	var lastX = 0;
	var lastY = 0;
	for( var i = 0; i < freqByteData.length; i++)
	{
		ctx.beginPath();
		ctx.strokeStyle =  "red";
		ctx.moveTo( lastX, lastY);
		ctx.lineTo( i * width, 255 - freqByteData[i]);
		lastY = 255 - freqByteData[i];
		lastX = i * width;
		ctx.stroke();
		ctx.fillRect(i * width, 255 - freqByteData[i], width, freqByteData[i]);
	}
}

/**
 * Get a random song from an ajax call.
 *
 * The call will simply return a string with the name of the song.
 * The JS library will do all the magic afterwards.
 */
function ajaxGetSong( )
{
		$.ajax(
			{
				url : "/song/",
				type : "POST",
				data : { a : "rand"}
			}
		).done(function( data )
			{
				next( data );
			});
}

/**
 * Returns an array of all the songs currently held on the server.
 * This list updates all the time, as songs are sorted alphabetically they won't
 * always be in the same index as they were.
 */
function getSongList( )
{
    $.ajax(
        {
            url : "/song/",
            type : "POST",
            data : { songList : "yay"}
        }
    ).done( function( data )
        {
            return data;
        });
}

$( document ).ready( function()
{
    if( src == "" )
    {
	    ajaxGetSong();
    }
});