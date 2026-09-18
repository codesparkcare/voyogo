import re

with open('../Voyogo_API_Logs_20260909_125004.txt', 'r') as f:
    content = f.read()

steps = re.split(r'(?=-{80}\nSTEP #)', content)

for i, step in enumerate(steps):
    if 'STEP #' in step:
        match = re.search(r'STEP #(\d+)', step)
        if match:
            step_num = match.group(1)
            api_name = 'Unknown'
            api_match = re.search(r'API NAME / ACTION:\s*(\w+)', step)
            if api_match:
                api_name = api_match.group(1)
            elif 'travelportalapi.benzyinfotech.com/api/hotels/search/init' in step:
                api_name = 'Init'
            elif '/rate' in step:
                api_name = 'HotelRate'
            elif 'content' in step:
                api_name = 'HotelContent'
            elif '/rooms' in step:
                api_name = 'MoreRooms'
            elif '/price/' in step:
                api_name = 'Pricing'
                
            filename = f"Step_{step_num}_{api_name}.txt"
            with open(filename, 'w') as out_f:
                out_f.write(step.strip() + '\n')
