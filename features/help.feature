Feature: Get Command Help
  As a rota member
  I want to use the help command
  To see what commands are available to me

  Scenario: User sends help command
    Given I am a rota user
    When I type "/rota help"
    Then I should see
    """
/rota <command>
`cancel` [date]: Cancel rota for today, or on date specified (Y-m-d)
`hello`: Say hello!
`help`: Display this help text
`join`: Join rota
`kick` <person>: Remove person from rota
`leave`: Leave rota
`rota`: Show the upcoming rota
`skip`: Skip current member, and pull remaining rota forwards
`swap` [member1] [member2]: Swap shopping duty between member1 and member2. Without member2 specified, member1 is swapped with current member. With no members specified today and next day are swapped
`who`: Whose turn it is today
    """

    Scenario: User sends incorrect command
      When I type "/rota asiudhoaisdh"
      Then I should see
      """
/rota <command>
`cancel` [date]: Cancel rota for today, or on date specified (Y-m-d)
`hello`: Say hello!
`help`: Display this help text
`join`: Join rota
`kick` <person>: Remove person from rota
`leave`: Leave rota
`rota`: Show the upcoming rota
`skip`: Skip current member, and pull remaining rota forwards
`swap` [member1] [member2]: Swap shopping duty between member1 and member2. Without member2 specified, member1 is swapped with current member. With no members specified today and next day are swapped
`who`: Whose turn it is today
    """
