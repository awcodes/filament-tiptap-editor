export function createColumn(colType, colContent = null) {
  if (colContent) {
    return colType.createChecked(null, colContent);
  }

  return colType.createAndFill();
}
