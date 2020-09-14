Feature: Get Command Help
  As a rota member
  I want to use the help command
  To see what commands are available to me

  Scenario: User sends help command
    Given I am a rota user
    When I send the text "/rota help"
    Then I should receive a direct response of
    """
/rota <operation>
`cancel` [date]: Cancel rota for today, or on date specified (Y-m-d)
`hello`: Say hello!
`help`: Display this help text
`join` [username]: Join the rota. Add [username] to add a specific person
`kick` <person>: Remove person from rota
`leave`: Leave rota
`ping`: Return a pong response
`rota`: Show the upcoming rota
`skip`: Skip current member, and pull remaining rota forwards
`swap` [member1] [member2]: Swap shopping duty between member1 and member2. Without member2 specified, member1 is swapped with current member. With no members specified today and next day are swapped
`who`: Whose turn it is today
    """

    Scenario: User sends incorrect command
      Given I am a rota user
      When I send the text "/rota asiudhoaisdh"
      Then I should receive a direct response of
      """
/rota <operation>
`cancel` [date]: Cancel rota for today, or on date specified (Y-m-d)
`hello`: Say hello!
`help`: Display this help text
`join` [username]: Join the rota. Add [username] to add a specific person
`kick` <person>: Remove person from rota
`leave`: Leave rota
`ping`: Return a pong response
`rota`: Show the upcoming rota
`skip`: Skip current member, and pull remaining rota forwards
`swap` [member1] [member2]: Swap shopping duty between member1 and member2. Without member2 specified, member1 is swapped with current member. With no members specified today and next day are swapped
`who`: Whose turn it is today
    """
