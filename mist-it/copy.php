
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Copy a text or image to Clipboard</title>
    <script>
    try {
      navigator.clipboard.write([
          new ClipboardItem({
              'image/png': pngImageBlob
          })
      ]);
  } catch (error) {
      console.error(error);
  }

  function copyImage() {
    var imgCap = document.getElementById('imgCap');
    var imgCanvas = document.createElement('canvas');

    imgCanvas.id = 'imgCanvas';
    imgCanvas.height = 40;
    imgCanvas.width = 120;

    document.body.appendChild(imgCanvas);
    var originalContext = imgCanvas.getContext('2d');
    originalContext.drawImage(imgCap, 0, 0);
  //return imgCanvas.toDataURL();
}


</script>
<style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, Helvetica, sans-serif;
    }

    body * {
      box-sizing: border-box;
    }

    button {
      cursor: pointer;
    }

    button:disabled {
      cursor: not-allowed;
    }

    img {
      max-width: 100%;
      display: block;
    }

    .container {
      max-width: 1220px;
      padding-right: 40px;
      padding-left: 40px;
      margin: 60px auto;
    }

    h1 {
      text-align: center;
      color: #444444;
    }

    h1 + p {
      color: #444444;
      text-align: center;
      max-width: 750px;
      margin: 10px auto;
    }

    .wrapper-block-copy {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-wrap: wrap;
    }

    .block-copy {
      border: 1px solid #979797;
      width: 200px;
      height: 200px;
      margin: 10px;
    }

    .wrapper-block-copy .block-copy img,
    .wrapper-block-copy .block-copy textarea {
      margin: 0;
      border: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .wrapper-block-copy .block-copy textarea {
      font-family: initial;
      padding: 5px;
      resize: none;
    }

    .wrapper-btn-copy {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .wrapper-btn-copy button {
      width: 200px;
      margin: 4px;
      padding: 12px 20px;
      background-color: #d8d8d8;
      border: 1px solid grey;
      text-transform: uppercase;
      font-size: 11px;
    }

    .status p {
      text-align: center;
    }

    .status {
      margin: 40px 20px;
    }

    .status p#errorMsg {
      color: red;
    }

    .status p span {
      font-weight: bold;
    }

    .content-result {
      width: 100%;
      height: 400px;
      border: 1px dotted grey;
      overflow-y: auto;
      margin: auto;
      position: relative;
      padding: 2px 4px;
      cursor: copy;
    }

    .content-result::after {
      position: absolute;
      content: 'Paste here';
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: rgb(145, 145, 145);
      cursor: copy;
    }

    .content-result:not(:empty)::after,
    .content-result:focus::after {
      display: none;
    }

    .content-result * {
      max-width: 100%;
      border: 0;
    }
    img {
      border: 1px solid black;
    }

</style>
  </head>
  <body>
    <div class="container">
      
      <div class="status">
        <p id="errorMsg"></p>
      </div>
      <div contenteditable="true" class="content-result"></div>
    </div>
    <p onload="copyImage();">copyImage copyImage</p>

  </body>
</html>
