export function getGridBuilderNodeTypes(schema) {
  if (schema.cached.gridBuilderNodeTypes) {
    return schema.cached.gridBuilderNodeTypes;
  }

  const roles = {};

  Object.keys(schema.nodes).forEach((type) => {
    const nodeType = schema.nodes[type];

    if (nodeType.spec.gridBuilderRole) {
      roles[nodeType.spec.gridBuilderRole] = nodeType;
    }
  });

  schema.cached.gridBuilderNodeTypes = roles;

  return roles;
}
