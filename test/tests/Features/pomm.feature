Feature: Pomm bridge for api platform

    Scenario: Index
        When I send a GET request to "/api"
        Then the JSON should be equal to:
        """
        {
            "@context": "/api/contexts/Entrypoint",
            "@id": "/api",
            "@type": "Entrypoint",
            "config": "/api/configs"
        }
        """

    Scenario: Retreive an empty collection
        When I send a GET request to "/api/configs"
        Then the JSON should be equal to:
        """
        {
            "@context": "/api/contexts/Config",
            "@id": "/api/configs",
            "@type": "hydra:Collection",
            "hydra:member": [
            ],
            "hydra:totalItems": 0
        }
        """

    Scenario Outline: Create a resource
        Given I add "Content-Type" header equal to "application/ld+json"
        When I send a POST request to "/api/configs" with body:
        """
        {
            "name": "<name>",
            "value": "<value>"
        }
        """

        Then the response status code should be 201
        And the JSON should be equal to:
        """
        {
            "@context": "/api/contexts/Config",
            "@id": "/api/configs",
            "@type": "hydra:Collection",
            "hydra:member": [
                "<name>",
                "<value>"
            ]
        }
        """

        Examples:
            | name  | value  |
            | test1 | value1 |
            | test2 | value2 |
            | test3 | value3 |
            | test4 | value4 |

    Scenario: Retreive a collection
        When I send a GET request to "/api/configs"
        Then the JSON should be equal to:
        """
        {
            "@context": "/api/contexts/Config",
            "@id": "/api/configs",
            "@type": "hydra:Collection",
            "hydra:member": [
                {
                    "name": "test1",
                    "value": "value1"
                },
                {
                    "name": "test2",
                    "value": "value2"
                },
                {
                    "name": "test3",
                    "value": "value3"
                },
                {
                    "name": "test4",
                    "value": "value4"
                }
            ],
            "hydra:totalItems": 4
        }
        """

    Scenario: Retreive resources order by desc
        When I send a GET request to "/api/configs?order[value]=desc"
        Then the JSON should be equal to:
        """
        {
            "@context": "/api/contexts/Config",
            "@id": "/api/configs",
            "@type": "hydra:Collection",
            "hydra:member": [
                {
                    "name": "test4",
                    "value": "value4"
                },
                {
                    "name": "test3",
                    "value": "value3"
                },
                {
                    "name": "test2",
                    "value": "value2"
                },
                {
                    "name": "test1",
                    "value": "value1"
                }
            ],
            "hydra:totalItems": 4,
            "hydra:view": {
                "@id": "/api/configs?order%5Bvalue%5D=desc",
                "@type": "hydra:PartialCollectionView"
            }
        }
        """

    Scenario: Modify a resource
        Given I add "Content-Type" header equal to "application/ld+json"
        When I send a PUT request to "/api/configs/test1" with body:
        """
        {
            "name": "test1",
            "value": "new_value"
        }
        """
        Then the response status code should be 200
        And the JSON should be equal to:
        """
        {
            "@context": "/api/contexts/Config",
            "@id": "/api/configs",
            "@type": "hydra:Collection",
            "hydra:member": [
                "test1",
                "new_value"
            ]
        }
        """

    Scenario: Retreive a resource
        When I send a GET request to "/api/configs/test1"
        Then the JSON should be equal to:
        """
        {
            "@context": "/api/contexts/Config",
            "@id": "/api/configs",
            "@type": "hydra:Collection",
            "hydra:member": [
                "test1",
                "new_value"
            ]
        }
        """

    Scenario: Retreive resources with search filter
        When I send a GET request to "/api/configs?value=new_value"
        Then the JSON should be equal to:
        """
        {
            "@context": "/api/contexts/Config",
            "@id": "/api/configs",
            "@type": "hydra:Collection",
            "hydra:member": [
                {
                    "name": "test1",
                    "value": "new_value"
                }
            ],
            "hydra:totalItems": 1,
            "hydra:view": {
                "@id": "/api/configs?value=new_value",
                "@type": "hydra:PartialCollectionView"
            }
        }
        """

    Scenario Outline: Delete a resource
        When I send a DELETE request to "/api/configs/<id>"
        Then the response status code should be 204

        Examples:
            | id    |
            | test1 |
            | test2 |
            | test3 |
            | test4 |

    Scenario:
        When I send a GET request to "/api/configs"
        Then the JSON should be equal to:
        """
        {
            "@context": "/api/contexts/Config",
            "@id": "/api/configs",
            "@type": "hydra:Collection",
            "hydra:member": [
            ],
            "hydra:totalItems": 0
        }
        """
