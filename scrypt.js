function calculateDamage() {
    const attack = parseInt(document.getElementById("attack").value) || 0;
    const defense = parseInt(document.getElementById("defense").value) || 0;
    const damage = parseFloat(document.getElementById("damage").value) || 0;

    const modifier = 1 + 0.05 * (attack - defense);
    const totalDamage = damage * modifier;

    document.getElementById("result").textContent =
        "Сумарний урон: " + totalDamage.toFixed(2);
}