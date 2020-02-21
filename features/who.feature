Feature: Rota users can see whose turn it is today
  As a rota user
  I want to see who's turn it is
  So they can be reminded it is their turn

  Scenario: Who command issued
    Given I am a rota user
    And it is "test2" user's turn today
    When I send the text "/rota who"
    Then I should see the messenger response of
    """
    It is <@test2>'s turn today
    """
