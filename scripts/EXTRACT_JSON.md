# JSON EXTRACTION PROTOCOL

This document specifies the extraction logic for parsing structured JSON output from LLM responses in Swap Hub.

## 1. Purpose
Extract a single valid JSON object adhering to `contracts/TASK_OUTPUT_SCHEMA.md` from raw LLM output that may contain markdown, conversational preamble, thinking tags, or multiple code blocks.

## 2. Extraction Priority

### Priority 1: Markdown Code Block
Look for triple-backtick blocks with `json` tag:
````markdown
```json
{
  "task_id": "backend_agent",
  "verdict": "PASS",
  "summary": "...",
  "modified_files": [...]
}
```
````
If multiple ```json blocks exist, take the **last** one (models often write scratchpad JSON first, final verdict last).

### Priority 2: Generic Code Block
Look for triple-backtick blocks without language tag that contain a JSON object with `"task_id"` and `"verdict"`.

### Priority 3: Raw JSON Object (Brace Matching)
Scan the text for the last balanced `{` and `}` pair that contains:
- `"task_id"`
- `"verdict"`

Use a brace-counting parser to handle nested objects:
1. Find every `{` that could start a JSON object.
2. Track brace depth: `+1` on `{`, `-1` on `}`.
3. When depth reaches 0, extract the substring.
4. Attempt `json.loads()`.
5. Check for required keys: `task_id`, `verdict`.
6. Return the last valid matching object found.

### Priority 4: Fallback Error Object
If no valid JSON can be extracted, generate a synthetic failure object:
```json
{
  "task_id": "unknown",
  "verdict": "BLOCKED",
  "summary": "FAILED_TO_EXTRACT_JSON: LLM response did not contain valid task output JSON",
  "modified_files": [],
  "feedback_notes": "Raw output preserved in agent log"
}
```

## 3. Validation
Extracted JSON must be validated against `contracts/TASK_OUTPUT_SCHEMA.md`:
- `task_id` must be non-empty string.
- `verdict` must be one of: `PASS`, `REJECT`, `BLOCKED`.
- If validation fails, treat as Priority 4 failure.
