export function getGridNodeTypes(schema) {
  if (schema.cached.gridNodeTypes) {
    return schema.cached.gridNodeTypes;
  }

  const roles = {};

  Object.keys(schema.nodes).forEach((type) => {
    const nodeType = schema.nodes[type];

    if (nodeType.spec.gridRole) {
      roles[nodeType.spec.gridRole] = nodeType;
    }
  });

  schema.cached.gridNodeTypes = roles;

  return roles;
}
