var FFTSIZE = 1024;      // number of samples for the analyser node FFT, min 32
var TICK_FREQ = 20;     // how often to run the tick function, in milliseconds
var image = 0;
var assetsPath = "music/"; // Create a single item to load.
var src = "";  // set up our source
var soundInstance;      // the sound instance we create
var analyserNode;       // the analyser node that allows us to visualize the audio
var freqFloatData, freqByteData, timeByteData;  // arrays to retrieve data from analyserNode
var canvas = document.getElementById( "barCanvas" );
var ctx = canvas.getContext( "2d" );
var volume = 1;
var songList = [];
canvas.style.width = "100%";
canvas.style.height = "100%";
canvas.width  = canvas.offsetWidth;
canvas.height = canvas.offsetHeight;

var self = this;

function init( song ) {

	$( "#img" ).attr( "src", "/music/" + song.image );
	$( "#songTitle" ).html( song.name );

	if( song != "" )
	{
		if( assetsPath + src == assetsPath + song.name)
		{
			handleLoad(this);
			return;
		}
		else
		{
			src = song.name;
		}
	}
	if (window.top != window) {
		document.getElementById("header").style.display = "none";
	}

	// Web Audio only demo, so we register just the WebAudioPlugin and if that fails, display fail message
	if (!createjs.Sound.registerPlugins([createjs.WebAudioPlugin])) {
		document.getElementById("error").style.display = "block";
		//return;
	}

	// create a new stage and point it at our canvas:
	createjs.Sound.addEventListener("fileload", createjs.proxy(handleLoad,this)); // add an event listener for when load is completed
	createjs.Sound.registerSound(assetsPath + src );  // register sound, which preloads by default

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
	soundInstance.addEventListener( "complete" , createjs.proxy(playNextSong, this));
	soundInstance.volume = volume;
	// start the tick and point it at the window so we can do some work before updating the stage:
	createjs.Ticker.addEventListener("tick", tick);
	createjs.Ticker.setInterval(TICK_FREQ);
}

function next( song, image )
{
	this.image = image;

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
	ctx.fillStyle = "#3D2117";
	var width = Math.ceil(canvas.width / freqByteData.length)
	var lastX = 0;
	var lastY = 0;
	for( var i = 0; i < freqByteData.length; i++)
	{
		ctx.beginPath();
		ctx.strokeStyle =  "#67C5C2";
		ctx.moveTo( lastX, lastY);
		ctx.lineTo( i * width, canvas.height - timeByteData[i]);
		lastY = canvas.height - timeByteData[i] * 2;
		lastX = i * width;
		ctx.stroke();
		ctx.fillRect( i * width, 0, width, freqByteData[i] * 2);
	}
}

$( "#searchIn" ).keypress(function()
{
	var text = $(this).val();
	$.ajax(
	{
		url : "/get/",
		type : "POST",
		data : { filter : text, list : true }
	}).done(function( data )
	{
		data = JSON.parse( data );
		var search = $( "#search" );
		search.html( "" );
		songList = data;
		for( var i = 0; i < data.length; i++)
		{
			search.append( '<p class="songLink" song="' + data[ i ].id + '" >' + data[ i ].name + '</p>' );
		}
		$( ".songLink" ).click( function()
		{
			ajaxGetSong( $( this ).attr( "song" ) );
		});
	});
});

function changeVolume( volume )
{
	soundInstance.volume = volume / 100;
	this.volume = volume / 100;
}

$( "#finalizePull" ).click(function( event )
{
	var url = $( "#pullUrl" ).val();
	var tags = $( "#pullTags" ).val();
	var prefix = $( "#pullPrefix" ).val();
	var notes = $( "#pullNotes" ).val();
	var usePrefix = $( "#pullSmartTitle" ).is( ":checked" );

	if( url == "" )
	{
		alert( "Sorry; URL was left empty" );
		return;
	}
	event.preventDefault();
	$.ajax(
	{
		url : "/pull/",
		type : "POST",
		data : { url : url,
				 tags : tags,
				 prefix : prefix,
				 notes : notes,
				 usePrefix : usePrefix }
	}).done( function( data )
	{
		alert( data );
		$( "#overlay" ).fadeOut();
	});
});


function getRandomSong()
{
	$.ajax(
		{
			url : "/get/",
			type : "POST"
		}
	).done(function( data )
		{
			data = JSON.parse( data );
			console.log( data );
			self.next( data );
		});
}

function playNextSong()
{
	if( songList.length != 0 )
	{
		self.next( songList[ parseInt( IntegerMath.random() * songList.length ) ] );
	}
	else
	{
		getRandomSong();
	}
}

/**
 * Get a random song from an ajax call.
 *
 * The call will simply return a string with the name of the song.
 * The JS library will do all the magic afterwards.
 */
function ajaxGetSong( song )
{
	$.ajax(
		{
			url : "/get/",
			type : "POST",
			data : { id : song }
		}
	).done(function( data )
		{
			data = JSON.parse( data );
			console.log( data );
			self.next( data );
		});
}

$( document ).ready( function()
{
    setTimeout( function(){
        if( src == "" )
        {
	    ajaxGetSong();
	    soundInstance.volume = 0.5;
        }
    }, 500 );

	$( "#pull" ).click( function( event )
	{
		$( "#overlay" ).fadeIn();
		event.preventDefault();
		return false;
	});

	$( "#close" ).click( function ( event )
	{
		$( "#overlay" ).fadeOut();
		event.preventDefault();
		return false;
	});
});
