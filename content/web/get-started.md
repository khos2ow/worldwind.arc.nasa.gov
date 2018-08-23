---
title: "Get Started-Web WorldWind/NASA WorldWind"
date: 2017-11-14T11:17:24-08:00
draft: false
---

## Get Started

---
This is a quick guide to get started on Web WorldWind basics. This is what we're going to build:

{{% latestWorldWindScript url="https://files.worldwind.arc.nasa.gov/artifactory" repo="web" %}}
{{% getStarted_FinalResult %}}

To do it, we'll go over the following steps:

- • Loading and displaying a WorldWind globe
- • Setting up a development web server
- • Displaying some objects over the globe (placemarks and 3D shapes)
- • Displaying imagery from a 3rd party map service

---

### Loading and displaying a WorldWind globe

There are multiple ways to load the Web WorldWind library. The simplest is to include a script tag in an HTML page to link it directly from WorldWind's servers. Let's create a file named 'index.html' and add the following to it:

{{< highlight html >}}
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <canvas id="canvasOne" width="1024" height="768">
            Your browser does not support HTML5 Canvas.
        </canvas>
        <script 
        src="{{% latestArtifactoryPath url="https://files.worldwind.arc.nasa.gov/artifactory" repo="web" %}}/worldwind.min.js"
        type="text/javascript"></script>
        <script src="helloWorldWind.js" type="text/javascript">
        </script>
    </body>
</html>
{{< /highlight >}}

As shown above, within your body section and above the `script` tags, a canvas is needed where you want your globe to be, defining its `id`, `width` and `height`. You also want to include a message for browsers that do not support HTML5 Canvas.

Now we'll create our WorldWind globe through JavaScript. In this example, we'll create a 'helloWorldWind.js' file. In our JavaScript file, first we need to create a WorldWindow. The [WorldWindow object](https://nasaworldwind.github.io/WebWorldWind/WorldWindow.html) binds all Web WorldWind functionality to an HTML canvas via its `id`, which as defined in our previous HTML is `"canvasOne"`:

{{< highlight javascript >}}
var wwd = new WorldWind.WorldWindow("canvasOne");
{{< /highlight >}}

Now we can start manipulating our WorldWind instance through the WorldWindow. Let's add some imagery layers:

{{< highlight javascript >}}
wwd.addLayer(new WorldWind.BMNGOneImageLayer());
wwd.addLayer(new WorldWind.BMNGLandsatLayer());
{{< /highlight >}}

The first layer is a locally sourced, very low resolution version of [NASA's Blue Marble](https://visibleearth.nasa.gov/view_cat.php?categoryID=1484), which it may be used as a 'fallback' if Web WorldWind is not able to request online imagery layers, such as the second one ([Landsat imagery](https://landsat.gsfc.nasa.gov/) with higher resolution).

We will also add a compass, a coordinates display, and some view controls to the [WorldWindow](https://nasaworldwind.github.io/WebWorldWind/WorldWindow.html):

{{< highlight javascript >}}
wwd.addLayer(new WorldWind.CompassLayer());
wwd.addLayer(new WorldWind.CoordinatesDisplayLayer(wwd));
wwd.addLayer(new WorldWind.ViewControlsLayer(wwd));
{{< /highlight >}}

Note that in order for both the corresponding icons of these controls to be displayed, as well as the low resolution Blue Marble imagery, we need their image files to be sourced locally. These need to be inside an '/images' directory which needs to be a sibling of the library, _i.e._ exist in the same directory. The image files can be downloaded in [here]({{% latestArtifactoryPath url="https://files.worldwind.arc.nasa.gov/artifactory" repo="web" %}}/images.zip).

We have written a basic Web WorldWind app, but unlike the Java and Android versions of WorldWind, Web WorldWind is meant to be part of a web application, therefore we need a web server to visualize our work. If you're not familiar with local testing servers, [the Mozilla Development Network is a good place to start](https://developer.mozilla.org/en-US/docs/Learn/Common_questions/set_up_a_local_testing_server).

---

### Serving your application

If you do not already have a web server, you can use a minimal development web server built in [Node.js](https://en.wikipedia.org/wiki/Node.js):

- • Download and install [Node](https://www.nodejs.org).
- • Open a command line console in the root folder of your web app (where the 'index.html' file and your '/images' folder is located).
- • In the command line console, run `npm install http-server`.
- • Execute the command `http-server` to start the server.
- • In your web browsear, head to the address http://localhost/:8080.

Another option is to use [Python's http.server](https://docs.python.org/2/library/simplehttpserver.html):

- • Download and install [Python](https://www.python.org/) version 3.X.
- • Open a command line console in the root folder of your web app (where the 'index.html' file and your '/images' folder is located).
- • In the command line console, run `python -m http.server`.
- • In your web browser, head to the address http://localhost/:8000.

In either case, you should see in your web browser a globe like this:

{{% latestWorldWindScript url="https://files.worldwind.arc.nasa.gov/artifactory" repo="web" %}}
{{% getStarted_ServingTheGlobe %}}

---
### Our WorldWind globe so far

So now you have a WorldWind globe up an running in a local instance of a minimal web server. These are the contents of our web application up to this point:

index.html:
{{< highlight html >}}
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <canvas id="canvasOne" width="1024" height="768">
        Your browser does not support HTML5 Canvas.
    </canvas>
    <script src="https://files.worldwind.arc.nasa.gov/artifactory/web/0.9.0/worldwind.min.js" type="text/javascript"></script>
    <script src="helloWorldWind.js" type="text/javascript"></script>
</body>
</html>
{{< /highlight >}}

helloWorldWind.js:
{{< highlight javascript >}}
var wwd = new WorldWind.WorldWindow("canvasOne");

wwd.addLayer(new WorldWind.BMNGOneImageLayer());
wwd.addLayer(new WorldWind.BMNGLandsatLayer());

wwd.addLayer(new WorldWind.CompassLayer());
wwd.addLayer(new WorldWind.CoordinatesDisplayLayer(wwd));
wwd.addLayer(new WorldWind.ViewControlsLayer(wwd));
{{< /highlight >}}

You can easily add to the globe things like placemarks, tridimensional polygon volumes, and other surface shapes. As you may have inferred from our code so far, WorldWind's basic mechanism for displaying information on the globe is the [Layer](https://nasaworldwind.github.io/WebWorldWind/Layer.html). As we are about to see, Layers are not exclusively related to map imagery. It's in a Layer where we should store the different kinds of shapes that we desire.

---
### Drawing placemarks

Let's begin with adding a [placemark](https://nasaworldwind.github.io/WebWorldWind/Placemark.html). As with all shapes, let's create a layer to store it, and then add it to the [WorldWindow](https://nasaworldwind.github.io/WebWorldWind/WorldWindow.html):

{{< highlight javascript >}}
var placemarkLayer = new WorldWind.RenderableLayer("Placemark");
wwd.addLayer(placemarkLayer);
{{< /highlight >}}

Then we can create a [PlacemarkAttributes](https://nasaworldwind.github.io/WebWorldWind/PlacemarkAttributes.html) object to define the attributes for the placemark to have. We will use an image file to draw the placemark, and we want to draw a text label beneath the placemark image. We can customize the positioning of both the image and the text label via [Offsets](https://nasaworldwind.github.io/WebWorldWind/Offset.html), and we can define which color we desire for the text with a [WorldWind.Color](https://nasaworldwind.github.io/WebWorldWind/Color.html) object:

{{< highlight javascript >}}
var placemarkAttributes = new WorldWind.PlacemarkAttributes(null);

placemarkAttributes.imageOffset = new WorldWind.Offset(
    WorldWind.OFFSET_FRACTION, 0.3,
    WorldWind.OFFSET_FRACTION, 0.0);

placemarkAttributes.labelAttributes.color = WorldWind.Color.YELLOW;
placemarkAttributes.labelAttributes.offset = new WorldWind.Offset(
            WorldWind.OFFSET_FRACTION, 0.5,
            WorldWind.OFFSET_FRACTION, 1.0);

{{< /highlight >}}

Finally, we'll define which image to use for the placemark. We'll use a pushpin image included in the images bundle mentioned at the beginning. `WorldWind.configuration.baseUrl` provides us with a relative path to the root folder where Web WorldWind is being served:

{{< highlight javascript >}}
placemarkAttributes.imageSource = WorldWind.configuration.baseUrl + "images/pushpins/plain-red.png";
{{< /highlight >}}

We can now create the placemark proper and add it to our `placemarkLayer`. For this, we create a placemark object using `new WorldWind.Placemark(position, false, placemarkAttributes);`. Let's review each argument.

We will use a [WorldWind.Position](https://nasaworldwind.github.io/WebWorldWind/Position.html) object to define the placemark location over the globe in terms of [geographic coordinates](https://en.wikipedia.org/wiki/Geographic_coordinate_system): latitude, longitude, and altitude. Most placements in WorldWind are defined in terms of geographic coordinates.

Secondly, we'll define if we desire the placemark size to scale according to the camera zoom. In this case we want the placemark size to remain constant, so it's set to `false`.

The last argument binds the previously created `PlacemarkAttributes` to our placemark.

Hence, the next steps on our placemark creation look like:

{{< highlight javascript >}}
var position = new WorldWind.Position(55.0, -106.0, 100.0);
var placemark = new WorldWind.Placemark(position, false, placemarkAttributes);
{{< /highlight >}}

Using the `Placemark.label` property, we can define the text contents of it with a String. In this case, we'll display the placemark's latitude and longitude. We'll also want the placemark to always be drawn on top of other shapes placed over the globe.

{{< highlight javascript >}}
placemark.label = "Placemark\n" +
    "Lat " + placemark.position.latitude.toPrecision(4).toString() + "\n" +
    "Lon " + placemark.position.longitude.toPrecision(5).toString();
placemark.alwaysOnTop = true;
{{< /highlight >}}

Experiment with the placemark positioning and customization. Try putting a placemark at your house, for instance.

We have customized our placemark how we want. We can finally add it to the previously created `placemarkLayer`, which will display the contents of the Layer. We could keep adding more placemarks to `placemarkLayer` if we desire.

{{< highlight javascript >}}
placemarkLayer.addRenderable(placemark);
{{< /highlight >}}

The work so far should look like this:
{{% latestWorldWindScript url="https://files.worldwind.arc.nasa.gov/artifactory" repo="web" %}}
{{% getStarted_Placemark %}}

---
### Displaying 3D shapes

In WorldWind there are different types of tridimensional shapes, among them, the [Polygon](https://nasaworldwind.github.io/WebWorldWind/Polygon.html). We'll add one to our current example.

First we need a layer to store our Polygon shape. Let's create it and add it to the [WorldWindow](https://nasaworldwind.github.io/WebWorldWind/WorldWindow.html):

{{< highlight javascript >}}
var polygonLayer = new WorldWind.RenderableLayer();
wwd.addLayer(polygonLayer);
{{< /highlight >}}

As with the placemark, we'll define first the attributes for the polygon to have. We create a [WorldWind.ShapeAttributes](https://nasaworldwind.github.io/WebWorldWind/ShapeAttributes.html) object to then define colors for the shape interior and outline. Note that we're using a new notation for the interior with a flexible RGBA notation (in this case, three quarters-transparent turquiose blue). The outline is solid blue as defined with a [WorldWind.Color](https://nasaworldwind.github.io/WebWorldWind/Color.html) object, similar to what we did with the placemark. The outline is set to appear and a lightsource is applied to the tridimensional shape, so shading will be applied to it depending on the camera's view angle:

{{< highlight javascript >}}
var polygonAttributes = new WorldWind.ShapeAttributes(null);
polygonAttributes.interiorColor = new WorldWind.Color(0, 1, 1, 0.75);
polygonAttributes.outlineColor = WorldWind.Color.BLUE;
polygonAttributes.drawOutline = true;
polygonAttributes.applyLighting = true;
{{< /highlight >}}

Now, in terms of latitude, longitude and altitude, we define the boundaries of the polygon using an array of [WorldWind.Position](https://nasaworldwind.github.io/WebWorldWind/Position.html) objects. We can create many different shapes, including polygons with interior boundaries. In this case we'll create a triangle 70 km high from the Earth's surface:

{{< highlight javascript >}}
var boundaries = [];
boundaries.push(new WorldWind.Position(20.0, -75.0, 700000.0));
boundaries.push(new WorldWind.Position(25.0, -85.0, 700000.0));
boundaries.push(new WorldWind.Position(20.0, -95.0, 700000.0));
{{< /highlight >}}

We can now construct our polygon shape with these boundaries and attributes. We want to display our shape as an extrusion from the surface of the Earth, and we use the `polygon.extrude` attribute set to `true` for this. If set to `false`, we would see a hovering triangle without thickness instead. Then we finally add the polygon to the `polygonLayer`. As with the `placemarkLayer`, we could keep adding more shapes to it if we desire.

{{< highlight javascript >}}
var polygon = new WorldWind.Polygon(boundaries, polygonAttributes);
polygon.extrude = true;
polygonLayer.addRenderable(polygon);
{{< /highlight >}}

This is how the polygon looks as configured. To distinguish its 3D shape, you can tilt the camera with right click dragging (or three finger sweep if in a mobile device).

{{% latestWorldWindScript url="https://files.worldwind.arc.nasa.gov/artifactory" repo="web" %}}
{{% getStarted_Polygon %}}

---
We can also display more complex 3D shapes from external sources, like [COLLADA](https://www.khronos.org/collada/wiki/COLLADA) 3D model files. COLLADA is basically an open standard for 3D content that provides encoding of visual scenes including geometry, shaders and effects, physics, animation, kinematics, and even multiple version representations of the same asset. Let's add a COLLADA model to our current scene.

As usual, let's create a Layer and add it to the [WorldWindow](https://nasaworldwind.github.io/WebWorldWind/WorldWindow.html) to have a place where to store the 3D model:

{{< highlight javascript >}}
var modelLayer = new WorldWind.RenderableLayer();
wwd.addLayer(modelLayer);
{{< /highlight >}}

To load a COLLADA .dae file, we need a [WorldWind.ColladaLoader](https://nasaworldwind.github.io/WebWorldWind/ColladaLoader.html) object, which will asynchronously retrieve the file. We can create it defining a position for the 3D model to have (again, a [WorldWind.Position](https://nasaworldwind.github.io/WebWorldWind/Position.html) object in terms of latitude, longitude, and altitude) and a configuration object containing a `dirPath` attribute that points towards a folder where many .dae files may be located. Web WorldWind's repository contains a COLLADA .dae file (and its .png texture) which otherwise you can download [here](https://files.worldwind.arc.nasa.gov/artifactory/web/0.9.0/examples/collada_models/).

We feed both parameters to the `ColladaLoader` constructor as follows:

{{< highlight javascript >}}
var position = new WorldWind.Position(10.0, -125.0, 800000.0);
var config = {dirPath: WorldWind.configuration.baseUrl + 'examples/collada_models/duck/'};
var colladaLoader = new WorldWind.ColladaLoader(position, config);
{{< /highlight >}}

Note that this assumes a '/collada_models' folder as a sibling of the Web WorldWind library.

We can now use the `load` function of `ColladaLoader` to retrieve the model file that we desire. The first parameter is a string with the model's filename, and the second is a [_callback function_](https://developer.mozilla.org/en-US/docs/Glossary/Callback_function). Note that in this callback we're setting a scale for the model to have (we could also change its orientation), and we're asynchronously adding the 3D COLLADA model to the `modelLayer` only after ensuring that the `load` function executed successfully:

{{< highlight javascript >}}
colladaLoader.load("duck.dae", function (colladaModel) {
    colladaModel.scale = 9000;
    modelLayer.addRenderable(colladaModel);
});
{{< /highlight >}}

This should be the end result:

{{% latestWorldWindScript url="https://files.worldwind.arc.nasa.gov/artifactory" repo="web" %}}
{{% getStarted_Collada %}}

---
### Accessing a map imagery service

WorldWind supports different open standards for retrieving georeferenced map imagery from a server, among them [WMS (Web Map Service)](http://www.opengeospatial.org/standards/wms). Let's see how we can access such a service.

For this feature we will use the commonly used [jQuery library](https://jquery.com/). It's not strictly necessary, but it makes life easier. Let's make it the first library we load (before WorldWind itself and our helloWorldWind.js file) via a `<script>` tag in our HTML file:

{{< highlight html >}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
{{< /highlight >}}

Now, back in our JavaScript file, we'll retrieve an imagery layer from [NASA Earth Observations WMS](https://neo.sci.gsfc.nasa.gov/about/wms.php) (Thank you, GSFC folks!) We'll need the WMS address alongside its WMS's `GetCapabilities` request:

{{< highlight javascript >}}
var serviceAddress = "https://neo.sci.gsfc.nasa.gov/wms/wms?SERVICE=WMS&REQUEST=GetCapabilities&VERSION=1.3.0";
{{< /highlight >}}

Within that document, we can identify the different imagery layers that the service provides. We'll choose one that displays the average temperature data.
{{< highlight javascript >}}
var layerName = "MOD_LSTD_CLIM_M";
{{< /highlight >}}

Now let's define a function to create our layer from the XML document from the WMS.

{{< highlight javascript >}}
var createLayer = function (xmlDom) {
    var wms = new WorldWind.WmsCapabilities(xmlDom);
    var wmsLayerCapabilities = wms.getNamedLayer(layerName);
    var wmsConfig = WorldWind.WmsLayer.formLayerConfiguration(wmsLayerCapabilities);
    var wmsLayer = new WorldWind.WmsLayer(wmsConfig);
    wwd.addLayer(wmsLayer);
};
{{< /highlight >}}

Let's briefly go through what every instruction line does within the `createLayer` function, in order:

- • Creates a [WmsCapabilities](https://nasaworldwind.github.io/WebWorldWind/WMSCapabilities.html) object from the XML document.
- • Retrieves a [WmsLayerCapabilities](https://nasaworldwind.github.io/WebWorldWind/WmsLayerCapabilities.html) object by the desired layer name.
- • Constructs a configuration object from the `WmsLayerCapabilities` object
- • Creates the WMS Layer from the configuration object
- • Adds the layer to the WorldWindow

Now let's handle possible errors that may occur is something fails during the WMS request:
{{< highlight javascript >}}
var logError = function (jqXhr, text, exception) {
    console.log("There was a failure retrieving the capabilities document: " +
        text +
    " exception: " + exception);
};
{{< /highlight >}}

We finally have everything to do our request and display the imagery to Web WorldWind. This is where jQuery comes handy. [In a single instruction](https://api.jquery.com/jquery.get/) we're doing the [(otherwise very long)](https://www.w3schools.com/xml/xml_http.asp) xhttp request, pointing out what to do if it succeeds, and what to do if it fails:

{{< highlight javascript >}}
$.get(serviceAddress).done(createLayer).fail(logError);
{{< /highlight >}}

We should now visualize the average temperature imagery over land in the globe:

{{% latestWorldWindScript url="https://files.worldwind.arc.nasa.gov/artifactory" repo="web" %}}
{{% getStarted_WMS %}}

---
## In summary

Congratulations! You now have a good grasp of how to use core Web WorldWind concepts and features.

Every feature here and more is described thoroughly via code examples in our repository in the ['examples'](https://github.com/NASAWorldWind/WebWorldWind/tree/develop/examples) and ['apps'](https://github.com/NASAWorldWind/WebWorldWind/tree/develop/apps) folders. Be sure to also check out our [API documentation](https://worldwind.arc.nasa.gov/web/docs/) for detailed information on the objects and functions described here.

This is the final result in code:

**index.html:**
{{< highlight html >}}
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <canvas id="canvasOne" width="1024" height="768">
            Your browser does not support HTML5 Canvas.
        </canvas>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script 
        src="https://files.worldwind.arc.nasa.gov/artifactory/web/0.9.0/worldwind.min.js"
        type="text/javascript">
        </script>
        <script src="helloWorldWind.js" type="text/javascript"></script>
    </body>
</html>
{{< /highlight >}}

**helloWorldWind.js:**
{{< highlight javascript >}}
// Create a WorldWindow for the canvas.
var wwd = new WorldWind.WorldWindow("canvasOne");

wwd.addLayer(new WorldWind.BMNGOneImageLayer());
wwd.addLayer(new WorldWind.BMNGLandsatLayer());

wwd.addLayer(new WorldWind.CompassLayer());
wwd.addLayer(new WorldWind.CoordinatesDisplayLayer(wwd));
wwd.addLayer(new WorldWind.ViewControlsLayer(wwd));

// Add a placemark
var placemarkLayer = new WorldWind.RenderableLayer();
wwd.addLayer(placemarkLayer);

var placemarkAttributes = new WorldWind.PlacemarkAttributes(null);

placemarkAttributes.imageOffset = new WorldWind.Offset(
    WorldWind.OFFSET_FRACTION, 0.3,
    WorldWind.OFFSET_FRACTION, 0.0);

placemarkAttributes.labelAttributes.offset = new WorldWind.Offset(
    WorldWind.OFFSET_FRACTION, 0.5,
    WorldWind.OFFSET_FRACTION, 1.0);

placemarkAttributes.imageSource = WorldWind.configuration.baseUrl + "images/pushpins/plain-red.png";

var position = new WorldWind.Position(55.0, -106.0, 100.0);
var placemark = new WorldWind.Placemark(position, false, placemarkAttributes);

placemark.label = "Placemark\n" +
    "Lat " + placemark.position.latitude.toPrecision(4).toString() + "\n" +
    "Lon " + placemark.position.longitude.toPrecision(5).toString();
placemark.alwaysOnTop = true;

placemarkLayer.addRenderable(placemark);

// Add a polygon
var polygonLayer = new WorldWind.RenderableLayer();
wwd.addLayer(polygonLayer);

var polygonAttributes = new WorldWind.ShapeAttributes(null);
polygonAttributes.interiorColor = new WorldWind.Color(0, 1, 1, 0.75);
polygonAttributes.outlineColor = WorldWind.Color.BLUE;
polygonAttributes.drawOutline = true;
polygonAttributes.applyLighting = true;

var boundaries = [];
boundaries.push(new WorldWind.Position(20.0, -75.0, 700000.0));
boundaries.push(new WorldWind.Position(25.0, -85.0, 700000.0));
boundaries.push(new WorldWind.Position(20.0, -95.0, 700000.0));

var polygon = new WorldWind.Polygon(boundaries, polygonAttributes);
polygon.extrude = true;
polygonLayer.addRenderable(polygon);

// Add a COLLADA model
var modelLayer = new WorldWind.RenderableLayer();
wwd.addLayer(modelLayer);

var position = new WorldWind.Position(10.0, -125.0, 800000.0);
var config = {dirPath: WorldWind.configuration.baseUrl + 'examples/collada_models/duck/'};

var colladaLoader = new WorldWind.ColladaLoader(position, config);
colladaLoader.load("duck.dae", function (colladaModel) {
    colladaModel.scale = 9000;
    modelLayer.addRenderable(colladaModel);
});

// Add WMS imagery
var serviceAddress = "https://neo.sci.gsfc.nasa.gov/wms/wms?SERVICE=WMS&REQUEST=GetCapabilities&VERSION=1.3.0";
var layerName = "MOD_LSTD_CLIM_M";

var createLayer = function (xmlDom) {
    var wms = new WorldWind.WmsCapabilities(xmlDom);
    var wmsLayerCapabilities = wms.getNamedLayer(layerName);
    var wmsConfig = WorldWind.WmsLayer.formLayerConfiguration(wmsLayerCapabilities);
    var wmsLayer = new WorldWind.WmsLayer(wmsConfig);
    wwd.addLayer(wmsLayer);
};

var logError = function (jqXhr, text, exception) {
    console.log("There was a failure retrieving the capabilities document: " +
        text +
    " exception: " + exception);
};

$.get(serviceAddress).done(createLayer).fail(logError);
{{< /highlight >}}

<br>
