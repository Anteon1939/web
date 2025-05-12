<?php
// Підключення до бази даних
include 'db.php';

// Функція для отримання списку фракцій
function getFactions($conn) {
    $sql = "SELECT DISTINCT faction FROM units ORDER BY faction";
    $result = $conn->query($sql);
    $factions = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $factions[] = $row['faction'];
        }
    }
    return $factions;
}

// Функція для отримання списку юнітів за фракцією
function getUnitsByFaction($conn, $faction) {
    $units = [];
    if (!empty($faction)) {
        $sql = "SELECT name FROM units WHERE faction = '$faction' ORDER BY name";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $units[] = $row['name'];
            }
        }
    }
    return $units;
}

// Функція для отримання деталей юніта за назвою
function getUnitDetails($conn, $unitName) {
    if (!empty($unitName)) {
        $sql = "SELECT name, image_path, damage, attack, defense FROM units WHERE name = '$unitName'";
        $result = $conn->query($sql);
        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        }
    }
    return null;
}

// Отримуємо список усіх фракцій
$factions = getFactions($conn);

// Обробка вибору першого юніта
$selectedFaction1 = $_GET['faction1'] ?? '';
$units1 = getUnitsByFaction($conn, $selectedFaction1);
$selectedUnit1Name = $_GET['unit1'] ?? '';
$unitDetails1 = getUnitDetails($conn, $selectedUnit1Name);

// Обробка вибору другого юніта
$selectedFaction2 = $_GET['faction2'] ?? '';
$units2 = getUnitsByFaction($conn, $selectedFaction2);
$selectedUnit2Name = $_GET['unit2'] ?? '';
$unitDetails2 = getUnitDetails($conn, $selectedUnit2Name);

// Закриваємо підключення до бази даних
$conn->close();
?>

<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Герої Меча і Магії III - Порівняння</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .comparison-container {
            display: flex;
            gap: 20px;
        }
        .unit-selector {
            flex: 1;
        }
        .unit-details {
            border: 1px solid #ccc;
            padding: 15px;
            margin-top: 10px;
        }
        .unit-details img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <header class="head">
        <a href="index.html"> <img src="image/logo.webp" alt="Логотип" class="logo">
        <h1>Герої Меча і Магії III - Порівняння</a></h1>
        
    </header>
    <h1>Порівняння юнітів</h1>
<div class="comparison-container">
    <div class="unit-selector">
    <h2>Перший юніт</h2>
    <form method="GET">
        <select name="faction1" onchange="this.form.submit()">
            <option value="">Виберіть фракцію</option>
            <?php foreach ($factions as $faction): ?>
                <option value="<?php echo htmlspecialchars($faction); ?>" <?php if ($selectedFaction1 == $faction) echo 'selected'; ?>><?php echo htmlspecialchars($faction); ?></option>
            <?php endforeach; ?>
        </select>
        <?php if (!empty($units1)): ?>
            <select name="unit1" onchange="this.form.submit()">
                <option value="">Виберіть юніта</option>
                <?php foreach ($units1 as $unit): ?>
                    <option value="<?php echo htmlspecialchars($unit); ?>" <?php if ($selectedUnit1Name == $unit) echo 'selected'; ?>><?php echo htmlspecialchars($unit); ?></option>
                <?php endforeach; ?>
            </select>
        <?php elseif (!empty($selectedFaction1)): ?>
            <p>Не знайдено юнітів для фракції: <?php echo htmlspecialchars($selectedFaction1); ?></p>
        <?php endif; ?>

        <!-- Додаємо приховані поля для збереження вибору другого юніта -->
        <input type="hidden" name="faction2" value="<?php echo htmlspecialchars($selectedFaction2); ?>">
        <input type="hidden" name="unit2" value="<?php echo htmlspecialchars($selectedUnit2Name); ?>">
    </form>
    <?php if ($unitDetails1): ?>
        <div class="unit-details">
            <h3><?php echo htmlspecialchars($unitDetails1['name']); ?></h3>
            <?php if (!empty($unitDetails1['image_path'])): ?>
                <img src="<?php echo htmlspecialchars($unitDetails1['image_path']); ?>" alt="<?php echo htmlspecialchars($unitDetails1['name']); ?>">
            <?php endif; ?>
            <p>Атака: <?php echo htmlspecialchars($unitDetails1['attack']); ?></p>
            <p>Захист: <?php echo htmlspecialchars($unitDetails1['defense']); ?></p>
            <p>Урон: <?php echo htmlspecialchars($unitDetails1['damage']); ?></p>
        </div>
    <?php endif; ?>
</div>

<div class="unit-selector">
    <h2>Другий юніт</h2>
    <form method="GET">
        <select name="faction2" onchange="this.form.submit()">
            <option value="">Виберіть фракцію</option>
            <?php foreach ($factions as $faction): ?>
                <option value="<?php echo htmlspecialchars($faction); ?>" <?php if ($selectedFaction2 == $faction) echo 'selected'; ?>><?php echo htmlspecialchars($faction); ?></option>
            <?php endforeach; ?>
        </select>
        <?php if (!empty($units2)): ?>
            <select name="unit2" onchange="this.form.submit()">
                <option value="">Виберіть юніта</option>
                <?php foreach ($units2 as $unit): ?>
                    <option value="<?php echo htmlspecialchars($unit); ?>" <?php if ($selectedUnit2Name == $unit) echo 'selected'; ?>><?php echo htmlspecialchars($unit); ?></option>
                <?php endforeach; ?>
            </select>
        <?php elseif (!empty($selectedFaction2)): ?>
            <p>Не знайдено юнітів для фракції: <?php echo htmlspecialchars($selectedFaction2); ?></p>
        <?php endif; ?>

        <!-- Додаємо приховані поля для збереження вибору першого юніта -->
        <input type="hidden" name="faction1" value="<?php echo htmlspecialchars($selectedFaction1); ?>">
        <input type="hidden" name="unit1" value="<?php echo htmlspecialchars($selectedUnit1Name); ?>">
    </form>
    <?php if ($unitDetails2): ?>
        <div class="unit-details">
            <h3><?php echo htmlspecialchars($unitDetails2['name']); ?></h3>
            <?php if (!empty($unitDetails2['image_path'])): ?>
                <img src="<?php echo htmlspecialchars($unitDetails2['image_path']); ?>" alt="<?php echo htmlspecialchars($unitDetails2['name']); ?>">
            <?php endif; ?>
            <p>Атака: <?php echo htmlspecialchars($unitDetails2['attack']); ?></p>
            <p>Захист: <?php echo htmlspecialchars($unitDetails2['defense']); ?></p>
            <p>Урон: <?php echo htmlspecialchars($unitDetails2['damage']); ?></p>
        </div>
    <?php endif; ?>
</div>
</div>
<!-- ...інший HTML-код... -->
<div class="calculator">
    <h2>Обчислення урону</h2>
    <label for="attack">Атака першого юніта:</label>
    <input type="number" id="attack" value="0"><br>

    <label for="defense">Захист другого юніта:</label>
    <input type="number" id="defense" value="0"><br>

    <label for="damage">Урон першого юніта:</label>
    <input type="number" id="damage" value="0"><br>

    <button onclick="calculateDamage()">Обчислити</button>
    <p id="result">Результат </p>
</div>
<script src="scrypt.js"></script>

</body>
</html>