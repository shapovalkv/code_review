<script>
    const robotPositionY = 0.3
    const robotPositionZ = 1.6

    var { scene, engine } = createScene()

    importDefaultModels()

    function createScene() {
        var canvas = document.getElementById('renderCanvas');
        var engine = new BABYLON.Engine(canvas, true);
        var scene = new BABYLON.Scene(engine);

        scene.clearColor = new BABYLON.Color3(45, 1, 1);
        var camera = new BABYLON.ArcRotateCamera("Camera", Math.PI / 2, Math.PI / 2, 10, BABYLON.Vector3.Zero(), scene);

        camera.attachControl(canvas, true);
        camera.wheelPrecision = 100;
        camera.lowerRadiusLimit = 5;
        camera.upperRadiusLimit = 20;
        camera.panningSensibility = 0;

        var ground = BABYLON.MeshBuilder.CreateGround("ground", { width: 10, height: 10, subdivisions: 10 }, scene);
        var groundMaterial = new BABYLON.GridMaterial("groundMaterial", scene);

        groundMaterial.majorUnitFrequency = 5;
        groundMaterial.minorUnitVisibility = 0.5;
        groundMaterial.gridRatio = 1;
        groundMaterial.backFaceCulling = false;
        groundMaterial.mainColor = new BABYLON.Color3(1, 1, 1);
        groundMaterial.lineColor = new BABYLON.Color3(0.5, 0.5, 0.5);
        ground.material = groundMaterial;

        var light = new BABYLON.HemisphericLight("light", new BABYLON.Vector3(1, 1, 0), scene);
        light.intensity = 0.7;

        camera.setPosition(new BABYLON.Vector3(4, 13, 4));
        camera.setTarget(BABYLON.Vector3.Zero());
        canvas.parentElement.appendChild(document.getElementById("cameraIcon"));

        engine.runRenderLoop(function() {
            if(!scene.paused){
                scene.render();
            }
        });
        return { scene, engine }
    }

    const loadedMeshes = {};

    function importDefaultModels() {
        var defaultModulesPath = @json($defaultModelsPath);

        var robot = @json($robotModel);
        var gripper = @json($gripper);

        var promises = [];
        displayLoader()
        for (const [key, path] of Object.entries(defaultModulesPath)) {
            if (path) {
                const promise = ImportMesh(path, 1)
                    .then(mesh => {
                        loadedMeshes[key] = mesh;
                    })
                    .catch(error => {
                        console.error('Error loading scene:', error);
                    });

                promises.push(promise);
            }
        }

        Promise.all(promises)
            .then(() => {
                if (robot && robot.cad_model && gripper && gripper.cad_model) {
                    importRobot(robot.cad_model, gripper.cad_model);
                }
                closeLoader()
            });
    }

    function disposeMesh(key) {
        if (loadedMeshes[key]) {
            loadedMeshes[key].forEach(mesh => {
                mesh.dispose();
            });
            delete loadedMeshes[key];
        }
    }

    function importGripper(gripper, positionX, positionY, positionZ, axis)
    {
        disposeMesh('gripper')
        ImportMesh(gripper.path, 1, positionX, positionY, positionZ, axis)
            .then(mesh => {
                loadedMeshes['gripper'] = mesh;
            })
            .catch(error => {
                console.error('Error loading scene:', error);
            });
    }

    function ImportMesh(path, scaling, positionX = 0, positionY = 0, positionZ = 0, axis = null) {
        return new Promise((resolve, reject) => {
            BABYLON.SceneLoader.ImportMeshAsync('', path, '', scene)
                .then(result => {
                    let meshes = result.meshes;
                    meshes[0].scaling.scaleInPlace(scaling);
                    meshes[0].position = new BABYLON.Vector3(positionX, positionY, positionZ - 2.2);
                    switch (axis) {
                        case '-x':
                            meshes[0].rotate(BABYLON.Axis.X, -Math.PI / 2, BABYLON.Space.WORLD);
                            break;
                        case 'z':
                            meshes[0].rotate(BABYLON.Axis.Z, Math.PI / 4, BABYLON.Space.WORLD);
                            break;
                        case '-z':
                            meshes[0].rotate(BABYLON.Axis.Z, -Math.PI / 4, BABYLON.Space.WORLD);
                            break;
                    }
                    meshes[0].freezeWorldMatrix();
                    resolve(meshes);
                })
                .catch(error => {
                    reject(error);
                });
        });
    }

    function importRobot(robotModel, gripperModel)
    {
        var path = robotModel.path
        var positionY = robotPositionY
        var positionZ = robotPositionZ

        ImportMesh(path, 1, 0, positionY, positionZ)
            .then(mesh => {
                loadedMeshes['robot_id'] = mesh;
                importGripper(
                    gripperModel,
                    robotModel.position_x,
                    robotModel.position_y + robotPositionY,
                    robotModel.position_z + robotPositionZ,
                    robotModel.rotate_axis
                )
                closeLoader()
            })
            .catch(error => {
                console.error('Error loading scene:', error);
                closeLoader()
            });
    }

    function displayLoader()
    {
        const loaderElement = document.getElementById('loader');
        const overlayElement = document.getElementById('scene-overlay');

        loaderElement.style.display = 'block';
        overlayElement.style.display = 'block';

        setTimeout(() => {
            loaderElement.style.opacity = '1';
            overlayElement.style.opacity = '1';
        }, 50);
    }

    function closeLoader()
    {
        const loaderElement = document.getElementById('loader');
        const overlayElement = document.getElementById('scene-overlay');

        loaderElement.style.opacity = '0';
        overlayElement.style.opacity = '0';

        setTimeout(() => {
            loaderElement.style.display = 'none';
            overlayElement.style.display = 'none';
        }, 300);
    }

    var scrollableDiv = document.getElementById('renderCanvas');

    scrollableDiv.addEventListener('mouseenter', function() {
        this.dataset.scrollTop = this.scrollTop;

        document.body.style.overflow = 'hidden';
    });

    scrollableDiv.addEventListener('mouseleave', function() {
        document.body.style.overflow = '';

        this.scrollTop = this.dataset.scrollTop;
    });
</script>
