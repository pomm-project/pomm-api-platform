Feature: Pomm filter for api platform
  Background:
    Given I add "accept" header equal to "application/json"

  Scenario Outline: Create a resource
    Given I add "Content-Type" header equal to "application/ld+json"
    When I send a POST request to "/api/filters" with body:
        """
        {
            "name": "<name>",
            "value": "<value>",
            "value_partial": "<value_partial>",
            "value_start": "<value_start>",
            "value_end": "<value_end>",
            "value_word_start": "<value_word_start>",
            "value_ipartial": "<value_ipartial>"
        }
        """

    Then the response status code should be 201
    And the JSON should be equal to:
        """
        {
            "name": "<name>",
            "value": "<value>",
            "value_partial": "<value_partial>",
            "value_start": "<value_start>",
            "value_end": "<value_end>",
            "value_word_start": "<value_word_start>",
            "value_ipartial": "<value_ipartial>"
        }
        """

    Examples:
      | name  | value  | value_partial   | value_start | value_end       | value_word_start | value_ipartial  |
      | test1 | value1 | test            | test        | test_value      | test_value       | TEST_VALUE_TEST |
      | test2 | value2 | test            | value_test  | value_test      | test value_test  | test            |
      | test3 | value3 | test_value_test | test_value  | test_value_test | test test_value  | test            |
      | test4 | value4 | test            | test        | test            | value_test test  | test            |

  Scenario: Retreive resources with search filter and strategy exact
    When I send a GET request to "/api/filters?value=value1"
    Then the JSON should be equal to:
        """
        [
            {
                "name": "test1",
                "value": "value1",
                "value_partial": "test",
                "value_start": "test",
                "value_end": "test_value",
                "value_word_start": "test_value",
                "value_ipartial": "TEST_VALUE_TEST"
            }
        ]
        """

  Scenario: Retreive resources with search multiple filter
    When I send a GET request to "/api/filters?value[]=value1&value[]=value2"
    Then the JSON should be equal to:
        """
        [
            {
                "name": "test1",
                "value": "value1",
                "value_partial": "test",
                "value_start": "test",
                "value_end": "test_value",
                "value_word_start": "test_value",
                "value_ipartial": "TEST_VALUE_TEST"
            },
            {
                "name": "test2",
                "value": "value2",
                "value_partial": "test",
                "value_start": "value_test",
                "value_end": "value_test",
                "value_word_start": "test value_test",
                "value_ipartial": "test"
            }
        ]
        """

  Scenario: Retreive resources with search filter and strategy partial
    When I send a GET request to "/api/filters?value_partial=value"
    Then the JSON should be equal to:
        """
        [
            {
                "name": "test3",
                "value": "value3",
                "value_partial": "test_value_test",
                "value_start": "test_value",
                "value_end": "test_value_test",
                "value_word_start": "test test_value",
                "value_ipartial": "test"
            }
        ]
        """

  Scenario: Retreive resources with search filter and strategy start
    When I send a GET request to "/api/filters?value_start=value"
    Then the JSON should be equal to:
        """
        [
            {
                "name": "test2",
                "value": "value2",
                "value_partial": "test",
                "value_start": "value_test",
                "value_end": "value_test",
                "value_word_start": "test value_test",
                "value_ipartial": "test"
            }
        ]
        """

  Scenario: Retreive resources with search filter and strategy end
    When I send a GET request to "/api/filters?value_end=value"
    Then the JSON should be equal to:
        """
        [
            {
                "name": "test1",
                "value": "value1",
                "value_partial": "test",
                "value_start": "test",
                "value_end": "test_value",
                "value_word_start": "test_value",
                "value_ipartial": "TEST_VALUE_TEST"
            }
        ]
        """

  Scenario: Retreive resources with search filter and strategy word start
    When I send a GET request to "/api/filters?value_word_start=value"
    Then the JSON should be equal to:
        """
        [
            {
                "name": "test2",
                "value": "value2",
                "value_partial": "test",
                "value_start": "value_test",
                "value_end": "value_test",
                "value_word_start": "test value_test",
                "value_ipartial": "test"
            },
            {
                "name": "test4",
                "value": "value4",
                "value_partial": "test",
                "value_start": "test",
                "value_end": "test",
                "value_word_start": "value_test test",
                "value_ipartial": "test"
            }
        ]
        """

  Scenario: Retreive resources with search filter not declared
    When I send a GET request to "/api/filters?name=test1"
    Then the JSON should be equal to:
        """
        [
            {
                "name": "test1",
                "value": "value1",
                "value_partial": "test",
                "value_start": "test",
                "value_end": "test_value",
                "value_word_start": "test_value",
                "value_ipartial": "TEST_VALUE_TEST"
            },
            {
                "name": "test2",
                "value": "value2",
                "value_partial": "test",
                "value_start": "value_test",
                "value_end": "value_test",
                "value_word_start": "test value_test",
                "value_ipartial": "test"
            },
            {
                "name": "test3",
                "value": "value3",
                "value_partial": "test_value_test",
                "value_start": "test_value",
                "value_end": "test_value_test",
                "value_word_start": "test test_value",
                "value_ipartial": "test"
            },
            {
                "name": "test4",
                "value": "value4",
                "value_partial": "test",
                "value_start": "test",
                "value_end": "test",
                "value_word_start": "value_test test",
                "value_ipartial": "test"
            }
        ]
        """
  Scenario: Retreive resources with search filter and strategy ipartial
    When I send a GET request to "/api/filters?value_ipartial=VaLuE"
    Then the JSON should be equal to:
        """
        [
            {
                "name": "test1",
                "value": "value1",
                "value_partial": "test",
                "value_start": "test",
                "value_end": "test_value",
                "value_word_start": "test_value",
                "value_ipartial": "TEST_VALUE_TEST"
            }
        ]
        """