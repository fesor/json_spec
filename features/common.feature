Feature: Common actions

  Background:
    Given the JSON is:
    """
    {
      "array": [
        "json",
        "spec"
      ],
      "created_at": "2011-07-08 02:27:34",
      "empty_array": [

      ],
      "empty_hash": {
      },
      "false": false,
      "float": 10.0,
      "hash": {
        "json": "spec"
      },
      "id": 1,
      "integer": 10,
      "negative": -10,
      "null": null,
      "string": "json_spec",
      "true": true,
      "updated_at": "2011-07-08 02:28:50"
    }
    """

  Scenario: Mix formats equivalence with inclusion table
    When I get the JSON
    Then the JSON at "created_at" should be "2011-07-08 02:27:34"
    And the JSON should have the following:
      | hash/json |

  Scenario: Mix formats equivalence with inclusion inline
    When I get the JSON
    Then the JSON at "created_at" should be "2011-07-08 02:27:34"
    And the JSON should have "negative"
