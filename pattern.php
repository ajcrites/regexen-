<?php
preg_match_all('%
    <a\s                    # start of anchor tag
                            #   tag names are followed by a space.  \s is a regex escape sequence
                            #   in PHP/PCRE that acts as a substitute for a character class
                            #   containing spaces, tabs, newlines, and other whitespace characters.
                            #   These are distinct from *string* escape sequences such as `\t` for tab

    .*?                     # zero or more characters (except newlines)
                            #   Match lazily/reluctantly/as little as possible to satisfy the rest of the pattern

    href                    # a literal "href" -- h-followed-by-r-followed-by-e-followed-b-f

    \s*                     # zero or more spaces (includes newlines)
                            #   * is the "zero or more" quantifier.  (+ is "one or more")

    =                       # a literal =

    \s*                     # zero or more spaces (including newlines)

    .??                     # reluctantly and optionally match a single character

    (                       # start a capture group

        (?<=                # start a lookbehind sequence

            "               # zero width assertion ... where we are, we are looking back to see
                            #   if we were preceded by a double quote.
                            #   The backslash is for escaping inside the PHP string literal
                            #   The regex never actually sees it

        )                   # end of lookbehind

        .*?                 # reluctantly match anything (except newlines)

        (?=                 # start a lookahead sequence
                            #   zero width assertion from the spot that follows where we are

            "               # literal "

        )                   # end lookahead

        |                   # alternation -- The pattern can either match everything before the pipe
                            #   up to the (, or everything after it up to the next | or ).  That is,
                            #   the regex has multiple possible patterns to choose from

        (?<=\').*?(?=\')    # mostly identical to the previous alternative, just for single quotes instead
                            #   we have to cover this case since both characters can be used as attribute value
                            #   delimiters in HTML.  (?<=[\'"]).*?(?=[\'"]) would not work because attribute values
                            #   may contain single or double quotes
                            #   Note that the backslashes are because we are in a single quoted PHP string.
                            #   This is so we can use single quotes in the string -- the regex never sees these backslashes

            |               # another alternation

        [^"\']+?            # character class.  Match anything *except* a single or double quote.  The
                            #   ^ signifies this.  ["\'] would mean match *either* a single or double quote.
                            #   the +? means reluctantly match one or more of these non-quote characters.
                            #   newlines are included!

        (?=[ >])            # lookahead for either a space or a greater than.  This is not consumed/captured,
                            #   but the spot where we are should be followed by one of these guys

    )                       # end capture group
%xi', file_get_contents("sample.html"), $matches);
var_dump($matches);
