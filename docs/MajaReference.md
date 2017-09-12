# Maja Templates :: List of all Language constructs

## Content

| Construct | Description |
|-----------|-------------|
| `@maja:if`                  | Condition   |
| `@maja:repeat`              | Loop [optional: `indexBy <indexName>` |
| `@maja:foreach`             | Loop    |
| `@maja:attributes`          | Add Attributes |
| `@maja:class`               | Add CSS Classes |
| `@maja:html`                | Inject unescaped Html |
| `@maja:text`                | Inject escaped text  |
| `maja:define-macro [@name] [@params]` | Define a macro |
| `maja:use-macro [@name] [@p:<name>]`  | Run the macro  |
| `maja:html [@name]`         | Output unescaped HTMl |
| `maja:text [@name]`         | Output escaped Text   |
| `maja:continue [@maja:if]`     | (Inside loop) Continue a loop  |
| `maja:break [@maja:if]`     | (Inside loop) Break a loop  |
| `maja:define [@as] [@parse=YAML|JSON|MD|TEXT]` | Define a variable in local scope | 
| `call:<method> [@as] [@p:<name>]` | Call an api function |
| `p:<name>`                  | Define a Parameter |
|                             |                    |
| `maja:dump [@name]`         | Debug: Print out all variables available |



### Conditions

___USAGE___

```
<div maja:if="<condition>">
</div>
```

___EXAMPLE HTML___

```

```

___EXAMPLE YAML___

```
html:
  - div maja:if="some value"
    - Welcome
```

### Loops

```
<div maja:repeat="times [indexBy index]">
</div>
```

### Text Usage (Todo)

```
<!-- maja:if="<condition>" -->

<!-- /maja:if -->
```

### Macros

```
<maja:macro name="printNavi" params="node">
    {{ node.name }}
</maja:macro>

<call:printNavi>
    <p:node>Some name</p:node>
</call:printNavi>
```