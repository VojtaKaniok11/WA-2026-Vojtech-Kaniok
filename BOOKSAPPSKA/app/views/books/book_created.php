<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!-- <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> -->
    <title>Document</title>
</head>
<body>
    <div>
        <div>
            <h2>Přidat novou knihu</h2>
            <p>Vyplňte údaje a uložte knihu do databáze.</p>
        </div>

        <div>
            <form action="">
                <div>
                    <div>
                        <label for="title">Název knihy <span>*</span></label>
                        <input type="text" id="title" name="title" required="required">
                    </div>
                    <div>
                        <button type="submit">Uložit knihu do DB</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</body>
</html>