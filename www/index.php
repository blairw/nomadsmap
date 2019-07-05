
<head>
<title>Nomadsmap</title>

	<link rel="icon" type="image/png" href="extras/fugue/luggage-tag.png" sizes="16x16">
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<script src="node_modules/jquery/dist/jquery.min.js"></script>
	<link rel="stylesheet" href="node_modules/leaflet/dist/leaflet.css" />
	<script src="node_modules/leaflet/dist/leaflet.js"></script>
	<script src="node_modules/@elfalem/leaflet-curve/dist/leaflet.curve.js"></script>
	
	<script src="extras/10-apiroot.js?revision=10"></script>
	<script src="extras/30-curvature.js?revision=10"></script>
	<script src="extras/50-createmap.js?revision=10"></script>
	<style>
		body {
			margin: 0;
			font-family: "Verdana", sans-serif;
		}

		#floatingdash {
			position: fixed;
			top: 1rem;
			right: 1rem;
			z-index: 99;
			background-color: rgba(0,0,0,0.25);
			border-radius: 1rem;
			padding: 1rem;
			color: white;
		}
		#floatingdash h1 {
			margin: 0;
			font-size: 10pt;
		}
		#floatingdash p {
			font-size: 7pt;
		}
		#floatingdash a {
			color: yellow;
		}
		#floatingdash a:hover {
			color: rgb(230,230,50);
		}
		#mapid {
			height: 100vh;
			z-index: 50;
		}
	</style>
</head>
<body onload="bodyDidLoad()">
	<div id="floatingdash">
		<h1>NOMADSMAP - THE MOVEMENTS OF DIGITAL NOMADS</h1>
		<p>
			Data sourced from <a href="https://nomadlist.com/">Nomad List</a> as self-reported by users, 1999-2019.
			<br />Only showing trips (in either direction) with 5 or more entries.
			<br />Showing <span style="font-weight: bold; color: rgb(255,0,100);">major</span> trips (20+ entries)
			and <span style="font-weight: bold; color: rgb(20,120,230);">minor</span> trips (5+ entries).
		</p>
		<p>
			<strong>Produced by the Digital Work Research Group</strong>
			<br />Concept: <a href="https://business.sydney.edu.au/staff/schlagwein">Prof. Daniel Schlagwein</a>
			<br />Software Engineering (see <a href="https://github.com/blairw/nomadsmap">GitHub repo</a>): <a href="https://blair.wang/">Blair Wang</a>
			<br />Data Engineering: <a href="https://www.business.unsw.edu.au/our-people/julian-prester">Julian Prester</a>
		</p>
		<button onclick="redrawMapToggleAnimation()">toggle animations</button>
		<button onclick="redrawMapToggleDarkMode()">toggle dark mode</button>
	</div>
	<div id="mapid"></div>
</body>