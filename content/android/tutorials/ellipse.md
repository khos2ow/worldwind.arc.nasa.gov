---
title: "Ellipse"
date: 2018-02-02T16:17:14-06:00
draft: false
header: Ellipse
listdescription: "Demonstrates how to configure and add an Ellipse shape to the globe."
listimage: "/img/ww-android-ellipse.jpg"
---


## Ellipse

<img src="/img/ww-android-ellipse.jpg" class="img-responsive center-block">

This tutorial demonstrates how to configure an Ellipse shape and add it to the globe.

Ellipse uses the provided major radius, minor radius, heading, and center position to draw an Ellipse on the globe. The outline of the ellipse is computed in geographic space using great circle distance. The number of points used to build the outline is dynamic and based on the camera distance to the shape.

---

### EllipseFragment.java

The EllipseFragment class extends the BasicGlobeFragment and overrides the createWorldWindow method. Several Ellipse objects are created, customized with ShapeAttributes, and then added to a RenderableLayer on the WorldWindow.

Creating a Surface Ellipse object:
```java
// Create a surface ellipse with the default attributes, a 500km major-radius and a 300km minor-radius. Surface
// ellipses are configured with a CLAMP_TO_GROUND altitudeMode and followTerrain set to true.
Ellipse ellipse = new Ellipse(new Position(45, -120, 0), 500000, 300000);
ellipse.setAltitudeMode(WorldWind.CLAMP_TO_GROUND); // clamp the ellipse's center position to the terrain surface
ellipse.setFollowTerrain(true); // cause the ellipse geometry to follow the terrain surface
```

The Ellipse object is now fully configured and ready to be added to the WorldWindow for display on the globe:
```java
// Create a layer for the Sightline
RenderableLayer tutorialLayer = new RenderableLayer();
wwd.getLayers().addLayer(tutorialLayer);
tutorialLayer.addRenderable(ellipse);
```

The color of the interior, outline, and line widths can be customized using ShapeAttributes:
```java
// Create a surface ellipse with with custom attributes that make the interior 50% transparent and increase the
// outline width.
ShapeAttributes attrs = new ShapeAttributes();
attrs.setInteriorColor(new Color(1, 1, 1, 0.5f)); // 50% transparent white
attrs.setOutlineWidth(3);
ellipse = new Ellipse(new Position(45, -100, 0), 500000, 300000, attrs);
ellipse.setAltitudeMode(WorldWind.CLAMP_TO_GROUND); // clamp the ellipse's center position to the terrain surface
ellipse.setFollowTerrain(true); // cause the ellipse geometry to follow the terrain surface
tutorialLayer.addRenderable(ellipse);
```

3D Ellipse shapes are also possible:
```java
// Create an ellipse with the default attributes, an altitude of 200 km, and a 500km major-radius and a 300km
// minor-radius.
ellipse = new Ellipse(new Position(25, -120, 200e3), 500000, 300000);
tutorialLayer.addRenderable(ellipse);
```

3D Ellipse shapes have additional configuration possibilities including extrusion and verticals:
```java
// Create an ellipse with custom attributes that make the interior 50% transparent and an extruded outline with
// vertical lines
attrs = new ShapeAttributes();
attrs.setInteriorColor(new Color(1, 1, 1, 0.5f)); // 50% transparent white
attrs.setDrawVerticals(true);
ellipse = new Ellipse(new Position(25, -100, 200e3), 500000, 300000, attrs);
ellipse.setExtrude(true);
tutorialLayer.addRenderable(ellipse);
```
